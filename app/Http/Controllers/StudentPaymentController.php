<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\StudentBill;
use App\Models\StudentPayment;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class StudentPaymentController extends Controller
{
    public function index(Request $request)
    {
        $dojangIds = $this->accessibleDojangIds();

        $payments = StudentPayment::query()
            ->with([
                'bill:id,invoice_number,title,amount,paid_amount,remaining_amount,status',
                'dojang:id,name',
                'student:id,name,email',
                'receiver:id,name',
            ])
            ->when(! Auth::user()->hasRole('super_admin'), function ($query) use ($dojangIds) {
                $query->whereIn('dojang_id', $dojangIds);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->whereHas('bill', function ($billQuery) use ($request) {
                        $billQuery->where('invoice_number', 'like', '%' . $request->search . '%')
                            ->orWhere('title', 'like', '%' . $request->search . '%');
                    })
                    ->orWhereHas('student', function ($studentQuery) use ($request) {
                        $studentQuery->where('name', 'like', '%' . $request->search . '%')
                            ->orWhere('email', 'like', '%' . $request->search . '%');
                    });
                });
            })
            ->when($request->filled('dojang_id'), function ($query) use ($request, $dojangIds) {
                if (Auth::user()->hasRole('super_admin') || in_array((int) $request->dojang_id, $dojangIds)) {
                    $query->where('dojang_id', $request->dojang_id);
                }
            })
            ->when($request->filled('student_id'), function ($query) use ($request) {
                $query->where('student_id', $request->student_id);
            })
            ->when($request->filled('payment_method'), function ($query) use ($request) {
                $query->where('payment_method', $request->payment_method);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->when($request->filled('date_from'), function ($query) use ($request) {
                $query->whereDate('paid_at', '>=', $request->date_from);
            })
            ->when($request->filled('date_to'), function ($query) use ($request) {
                $query->whereDate('paid_at', '<=', $request->date_to);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('StudentPayments/Index', [
            'payments' => $payments,
            'filters' => [
                'search' => $request->search,
                'dojang_id' => $request->dojang_id,
                'student_id' => $request->student_id,
                'payment_method' => $request->payment_method,
                'status' => $request->status,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
            ],
            'dojangs' => $this->accessibleDojangs(),
            'students' => $this->accessibleStudents(),
            'paymentMethods' => $this->paymentMethods(),
            'statuses' => $this->statuses(),
        ]);
    }

    private function statuses(): array
    {
        return [
            'paid' => 'Lunas',
            'cancelled' => 'Dibatalkan',
            'refunded' => 'Dikembalikan',
        ];
    }

    private function accessibleDojangs()
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return Dojang::query()
                ->select('id', 'name')
                ->orderBy('name')
                ->get();
        }

        return Dojang::query()
            ->select('id', 'name')
            ->whereIn('id', $this->accessibleDojangIds())
            ->orderBy('name')
            ->get();
    }

    private function accessibleStudents()
    {
        return User::role('student')
            ->select('id', 'name', 'email', 'dojang_id')
            ->whereIn('dojang_id', $this->accessibleDojangIds())
            ->orderBy('name')
            ->get();
    }

    public function create(StudentBill $studentBill)
    {
        $this->ensureCanAccessDojang($studentBill->dojang_id);

        abort_if(
            in_array($studentBill->status, ['paid', 'cancelled', 'expired']),
            403,
            'Tagihan ini tidak dapat menerima pembayaran.'
        );

        $studentBill->load([
            'dojang:id,name',
            'room:id,name',
            'student:id,name,email',
            'category:id,name',
        ]);

        return Inertia::render('StudentPayments/Create', [
            'bill' => $studentBill,
            'paymentMethods' => $this->paymentMethods(),
        ]);
    }

    public function store(Request $request, StudentBill $studentBill)
    {
        $this->ensureCanAccessDojang($studentBill->dojang_id);

        abort_if(
            in_array($studentBill->status, ['paid', 'cancelled', 'expired']),
            403,
            'Tagihan ini tidak dapat menerima pembayaran.'
        );

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:' . $studentBill->remaining_amount,
            ],
            'payment_method' => [
                'required',
                Rule::in(array_keys($this->paymentMethods())),
            ],
            'paid_at' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $studentBill) {
            StudentPayment::create([
                'student_bill_id' => $studentBill->id,
                'dojang_id' => $studentBill->dojang_id,
                'student_id' => $studentBill->student_id,
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'status' => 'paid',
                'paid_at' => $validated['paid_at'],
                'note' => $validated['note'],
                'received_by' => Auth::id(),
            ]);

            $studentBill->refresh();
            $studentBill->recalculatePaymentStatus();
        });

        return redirect()
            ->route('student-bills.show', $studentBill)
            ->with('success', 'Pembayaran siswa berhasil dicatat.');
    }

    public function show(StudentPayment $studentPayment)
    {
        $this->ensureCanAccessDojang($studentPayment->dojang_id);

        $studentPayment->load([
            'bill:id,invoice_number,title,amount,paid_amount,remaining_amount,status',
            'dojang:id,name',
            'student:id,name,email',
            'receiver:id,name',
        ]);

        return Inertia::render('StudentPayments/Show', [
            'payment' => $studentPayment,
            'paymentMethods' => $this->paymentMethods(),
        ]);
    }

    public function edit(StudentPayment $studentPayment)
    {
        $this->ensureCanAccessDojang($studentPayment->dojang_id);

        abort_if($studentPayment->status !== 'paid', 403, 'Hanya pembayaran aktif yang dapat diedit.');

        $studentPayment->load([
            'bill:id,invoice_number,title,amount,paid_amount,remaining_amount,status',
            'dojang:id,name',
            'student:id,name,email',
        ]);

        return Inertia::render('StudentPayments/Edit', [
            'payment' => $studentPayment,
            'paymentMethods' => $this->paymentMethods(),
        ]);
    }

    public function update(Request $request, StudentPayment $studentPayment)
    {
        $this->ensureCanAccessDojang($studentPayment->dojang_id);

        abort_if($studentPayment->status !== 'paid', 403, 'Hanya pembayaran aktif yang dapat diedit.');

        $bill = $studentPayment->bill;

        $otherPaidAmount = $bill->payments()
            ->where('status', 'paid')
            ->where('id', '!=', $studentPayment->id)
            ->sum('amount');

        $maxAmount = max($bill->amount - $otherPaidAmount, 1);

        $validated = $request->validate([
            'amount' => [
                'required',
                'numeric',
                'min:1',
                'max:' . $maxAmount,
            ],
            'payment_method' => [
                'required',
                Rule::in(array_keys($this->paymentMethods())),
            ],
            'paid_at' => ['required', 'date'],
            'note' => ['nullable', 'string'],
        ]);

        DB::transaction(function () use ($validated, $studentPayment, $bill) {
            $studentPayment->update([
                'amount' => $validated['amount'],
                'payment_method' => $validated['payment_method'],
                'paid_at' => $validated['paid_at'],
                'note' => $validated['note'],
            ]);

            $bill->refresh();
            $bill->recalculatePaymentStatus();
        });

        return redirect()
            ->route('student-bills.show', $bill)
            ->with('success', 'Pembayaran siswa berhasil diperbarui.');
    }

    public function cancel(StudentPayment $studentPayment)
    {
        $this->ensureCanAccessDojang($studentPayment->dojang_id);

        abort_if($studentPayment->status !== 'paid', 403, 'Pembayaran ini tidak dapat dibatalkan.');

        $bill = $studentPayment->bill;

        DB::transaction(function () use ($studentPayment, $bill) {
            $studentPayment->update([
                'status' => 'cancelled',
            ]);

            $bill->refresh();
            $bill->recalculatePaymentStatus();
        });

        return redirect()
            ->route('student-bills.show', $bill)
            ->with('success', 'Pembayaran siswa berhasil dibatalkan.');
    }

    public function destroy(StudentPayment $studentPayment)
    {
        $this->ensureCanAccessDojang($studentPayment->dojang_id);

        $bill = $studentPayment->bill;

        DB::transaction(function () use ($studentPayment, $bill) {
            $studentPayment->delete();

            $bill->refresh();
            $bill->recalculatePaymentStatus();
        });

        return redirect()
            ->route('student-bills.show', $bill)
            ->with('success', 'Pembayaran siswa berhasil dihapus.');
    }

    private function paymentMethods(): array
    {
        return [
            'cash' => 'Cash',
            'transfer' => 'Transfer',
            'qris' => 'QRIS',
            'other' => 'Lainnya',
        ];
    }

    private function accessibleDojangIds(): array
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return Dojang::pluck('id')->toArray();
        }

        $query = Dojang::query();

        $query->where(function ($q) use ($user) {
            if (Schema::hasColumn('dojangs', 'owner_id')) {
                $q->orWhere('owner_id', $user->id);
            }

            if (Schema::hasColumn('dojangs', 'user_id')) {
                $q->orWhere('user_id', $user->id);
            }

            if (! empty($user->dojang_id)) {
                $q->orWhere('id', $user->dojang_id);
            }
        });

        return $query->pluck('id')->toArray();
    }

    private function ensureCanAccessDojang(int $dojangId): void
    {
        if (Auth::user()->hasRole('super_admin')) {
            return;
        }

        abort_unless(
            in_array($dojangId, $this->accessibleDojangIds()),
            403
        );
    }
}