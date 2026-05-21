<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\PaymentCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class PaymentCategoryController extends Controller
{
    public function index(Request $request)
    {
        $dojangIds = $this->accessibleDojangIds();

        $categories = PaymentCategory::query()
            ->with('dojang:id,name')
            ->when(! Auth::user()->hasRole('super_admin'), function ($query) use ($dojangIds) {
                $query->whereIn('dojang_id', $dojangIds);
            })
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })
            ->when($request->filled('dojang_id'), function ($query) use ($request, $dojangIds) {
                if (Auth::user()->hasRole('super_admin') || in_array((int) $request->dojang_id, $dojangIds)) {
                    $query->where('dojang_id', $request->dojang_id);
                }
            })
            ->when($request->filled('status'), function ($query) use ($request) {
                if ($request->status === 'active') {
                    $query->where('is_active', true);
                }

                if ($request->status === 'inactive') {
                    $query->where('is_active', false);
                }
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('PaymentCategories/Index', [
            'categories' => $categories,
            'filters' => [
                'search' => $request->search,
                'dojang_id' => $request->dojang_id,
                'status' => $request->status,
            ],
            'dojangs' => $this->accessibleDojangs(),
        ]);
    }

    public function create()
    {
        return Inertia::render('PaymentCategories/Create', [
            'dojangs' => $this->accessibleDojangs(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_categories')
                    ->where('dojang_id', $request->dojang_id),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $this->ensureCanAccessDojang((int) $validated['dojang_id']);

        PaymentCategory::create($validated);

        return redirect()
            ->route('payment-categories.index')
            ->with('success', 'Kategori pembayaran berhasil dibuat.');
    }

    public function show(PaymentCategory $paymentCategory)
    {
        $this->ensureCanAccessDojang($paymentCategory->dojang_id);

        $paymentCategory->load('dojang:id,name');

        return Inertia::render('PaymentCategories/Show', [
            'category' => $paymentCategory,
        ]);
    }

    public function edit(PaymentCategory $paymentCategory)
    {
        $this->ensureCanAccessDojang($paymentCategory->dojang_id);

        $paymentCategory->load('dojang:id,name');

        return Inertia::render('PaymentCategories/Edit', [
            'category' => $paymentCategory,
            'dojangs' => $this->accessibleDojangs(),
        ]);
    }

    public function update(Request $request, PaymentCategory $paymentCategory)
    {
        $this->ensureCanAccessDojang($paymentCategory->dojang_id);

        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('payment_categories')
                    ->where('dojang_id', $request->dojang_id)
                    ->ignore($paymentCategory->id),
            ],
            'description' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ]);

        $this->ensureCanAccessDojang((int) $validated['dojang_id']);

        $paymentCategory->update($validated);

        return redirect()
            ->route('payment-categories.index')
            ->with('success', 'Kategori pembayaran berhasil diperbarui.');
    }

    public function destroy(PaymentCategory $paymentCategory)
    {
        $this->ensureCanAccessDojang($paymentCategory->dojang_id);

        if ($paymentCategory->bills()->exists()) {
            return back()->with('error', 'Kategori tidak dapat dihapus karena sudah digunakan pada tagihan siswa.');
        }

        $paymentCategory->delete();

        return redirect()
            ->route('payment-categories.index')
            ->with('success', 'Kategori pembayaran berhasil dihapus.');
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
}