<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\PaymentCategory;
use App\Models\Room;
use App\Models\StudentBill;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class StudentBillController extends Controller
{
    public function index(Request $request)
    {
        $dojangIds = $this->accessibleDojangIds();

        $bills = StudentBill::query()
            ->with([
                'dojang:id,name',
                'room:id,name,dojang_id',
                'student:id,name,email',
                'category:id,name',
            ])
            ->when(! Auth::user()->hasRole('super_admin'), function ($query) use ($dojangIds) {
                $query->whereIn('dojang_id', $dojangIds);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where(function ($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%')
                        ->orWhere('invoice_number', 'like', '%' . $request->search . '%')
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
            ->when($request->filled('room_id'), function ($query) use ($request) {
                $query->where('room_id', $request->room_id);
            })
            ->when($request->filled('payment_category_id'), function ($query) use ($request) {
                $query->where('payment_category_id', $request->payment_category_id);
            })
            ->when($request->filled('period'), function ($query) use ($request) {
                $query->where('period', $request->period);
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                $query->where('status', $request->status);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('StudentBills/Index', [
            'bills' => $bills,
            'filters' => [
                'search' => $request->search,
                'dojang_id' => $request->dojang_id,
                'room_id' => $request->room_id,
                'payment_category_id' => $request->payment_category_id,
                'period' => $request->period,
                'status' => $request->status,
            ],
            'dojangs' => $this->accessibleDojangs(),
            'rooms' => $this->accessibleRooms(),
            'categories' => $this->accessiblePaymentCategories(),
            'statuses' => $this->statuses(),
        ]);
    }

    public function create()
    {
        return Inertia::render('StudentBills/Create', [
            'dojangs' => $this->accessibleDojangs(),
            'rooms' => $this->accessibleRooms(),
            'categories' => $this->accessiblePaymentCategories(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'room_id' => ['required', 'exists:rooms,id'],
            'payment_category_id' => ['nullable', 'exists:payment_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'period' => ['nullable', 'string', 'max:20'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
        ]);

        $this->ensureCanAccessDojang((int) $validated['dojang_id']);

        $room = Room::query()
            ->where('id', $validated['room_id'])
            ->where('dojang_id', $validated['dojang_id'])
            ->firstOrFail();

        if (! empty($validated['payment_category_id'])) {
            PaymentCategory::query()
                ->where('id', $validated['payment_category_id'])
                ->where('dojang_id', $validated['dojang_id'])
                ->firstOrFail();
        }

        $students = $this->activeStudentsInRoom($room);

        if ($students->isEmpty()) {
            return back()
                ->withInput()
                ->with('error', 'Tidak ada siswa aktif di room ini.');
        }

        $created = 0;
        $skipped = 0;

        DB::transaction(function () use ($validated, $students, &$created, &$skipped) {
            foreach ($students as $student) {
                $exists = StudentBill::query()
                    ->where('room_id', $validated['room_id'])
                    ->where('student_id', $student->id)
                    ->where('period', $validated['period'])
                    ->where('payment_category_id', $validated['payment_category_id'])
                    ->where('title', $validated['title'])
                    ->exists();

                if ($exists) {
                    $skipped++;
                    continue;
                }

                StudentBill::create([
                    'dojang_id' => $validated['dojang_id'],
                    'room_id' => $validated['room_id'],
                    'student_id' => $student->id,
                    'payment_category_id' => $validated['payment_category_id'],
                    'invoice_number' => $this->generateInvoiceNumber(),
                    'title' => $validated['title'],
                    'description' => $validated['description'],
                    'period' => $validated['period'],
                    'amount' => $validated['amount'],
                    'paid_amount' => 0,
                    'remaining_amount' => $validated['amount'],
                    'status' => 'unpaid',
                    'due_date' => $validated['due_date'],
                    'created_by' => Auth::id(),
                ]);

                $created++;
            }
        });

        return redirect()
            ->route('student-bills.index')
            ->with('success', "Generate tagihan selesai. Dibuat: {$created}, dilewati: {$skipped}.");
    }

    public function show(StudentBill $studentBill)
    {
        $this->ensureCanAccessDojang($studentBill->dojang_id);

        $studentBill->load([
            'dojang:id,name',
            'room:id,name',
            'student:id,name,email',
            'category:id,name',
            'creator:id,name',
            'payments.receiver:id,name',
        ]);

        return Inertia::render('StudentBills/Show', [
            'bill' => $studentBill,
            'statuses' => $this->statuses(),
        ]);
    }

    public function edit(StudentBill $studentBill)
    {
        $this->ensureCanAccessDojang($studentBill->dojang_id);

        abort_if($studentBill->status === 'paid', 403, 'Tagihan yang sudah lunas tidak dapat diedit.');

        return Inertia::render('StudentBills/Edit', [
            'bill' => $studentBill->load([
                'dojang:id,name',
                'room:id,name,dojang_id',
                'student:id,name,email',
                'category:id,name',
            ]),
            'categories' => $this->accessiblePaymentCategories($studentBill->dojang_id),
        ]);
    }

    public function update(Request $request, StudentBill $studentBill)
    {
        $this->ensureCanAccessDojang($studentBill->dojang_id);

        abort_if($studentBill->status === 'paid', 403, 'Tagihan yang sudah lunas tidak dapat diedit.');

        $validated = $request->validate([
            'payment_category_id' => ['nullable', 'exists:payment_categories,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'period' => ['nullable', 'string', 'max:20'],
            'amount' => ['required', 'numeric', 'min:0'],
            'due_date' => ['nullable', 'date'],
            'status' => ['required', Rule::in(['unpaid', 'partial', 'cancelled', 'expired'])],
        ]);

        if (! empty($validated['payment_category_id'])) {
            PaymentCategory::query()
                ->where('id', $validated['payment_category_id'])
                ->where('dojang_id', $studentBill->dojang_id)
                ->firstOrFail();
        }

        $paidAmount = $studentBill->payments()
            ->where('status', 'paid')
            ->sum('amount');

        abort_if(
            $validated['amount'] < $paidAmount,
            422,
            'Nominal tagihan tidak boleh lebih kecil dari total pembayaran yang sudah masuk.'
        );

        $remainingAmount = max($validated['amount'] - $paidAmount, 0);

        $status = $validated['status'];

        if ($paidAmount > 0 && $paidAmount < $validated['amount']) {
            $status = 'partial';
        }

        if ($paidAmount >= $validated['amount'] && $validated['amount'] > 0) {
            $status = 'paid';
        }

        if (in_array($validated['status'], ['cancelled', 'expired'])) {
            $status = $validated['status'];
        }

        $studentBill->update([
            'payment_category_id' => $validated['payment_category_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'period' => $validated['period'],
            'amount' => $validated['amount'],
            'paid_amount' => $paidAmount,
            'remaining_amount' => $remainingAmount,
            'status' => $status,
            'due_date' => $validated['due_date'],
        ]);

        return redirect()
            ->route('student-bills.show', $studentBill)
            ->with('success', 'Tagihan berhasil diperbarui.');
    }

    public function destroy(StudentBill $studentBill)
    {
        $this->ensureCanAccessDojang($studentBill->dojang_id);

        if ($studentBill->payments()->exists()) {
            return back()->with('error', 'Tagihan tidak dapat dihapus karena sudah memiliki transaksi pembayaran.');
        }

        $studentBill->delete();

        return redirect()
            ->route('student-bills.index')
            ->with('success', 'Tagihan berhasil dihapus.');
    }

    private function activeStudentsInRoom(Room $room)
    {
        return User::role('student')
            ->select('users.id', 'users.name', 'users.email', 'users.dojang_id')
            ->where('users.dojang_id', $room->dojang_id)
            ->whereHas('rooms', function ($query) use ($room) {
                $query->where('rooms.id', $room->id)
                    ->where('room_user.is_active', true);
            })
            ->orderBy('users.name')
            ->get();
    }

    private function generateInvoiceNumber(): string
    {
        do {
            $number = 'INV-STU-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
        } while (StudentBill::where('invoice_number', $number)->exists());

        return $number;
    }

    private function statuses(): array
    {
        return [
            'unpaid' => 'Belum Bayar',
            'partial' => 'Sebagian',
            'paid' => 'Lunas',
            'cancelled' => 'Dibatalkan',
            'expired' => 'Kadaluarsa',
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

    private function accessibleRooms()
    {
        return Room::query()
            ->select('id', 'name', 'dojang_id')
            ->whereIn('dojang_id', $this->accessibleDojangIds())
            ->orderBy('name')
            ->get();
    }

    private function accessiblePaymentCategories(?int $dojangId = null)
    {
        return PaymentCategory::query()
            ->select('id', 'name', 'dojang_id')
            ->whereIn('dojang_id', $this->accessibleDojangIds())
            ->when($dojangId, function ($query) use ($dojangId) {
                $query->where('dojang_id', $dojangId);
            })
            ->where('is_active', true)
            ->orderBy('name')
            ->get();
    }
}