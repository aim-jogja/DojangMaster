<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\User;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DojangController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $dojangs = Dojang::query()
            ->with(['owner:id,name,email', 'subscription.plan'])
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Dojangs/Index', [
            'dojangs' => $dojangs,
            'canCreate' => $user->hasRole('super_admin'),
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        abort_unless($user->hasRole('super_admin'), 403);

        $owners = User::role('owner')
            ->select('id', 'name', 'email')
            ->orderBy('name')
            ->get();

        return Inertia::render('Dojangs/Create', [
            'owners' => $owners,
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        abort_unless($user->hasRole('super_admin'), 403);

        $validated = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'domain' => ['required', 'string', 'max:255', 'unique:dojangs,domain'],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
        ]);

        $owner = User::findOrFail($validated['user_id']);

        abort_unless($owner->hasRole('owner'), 422, 'User yang dipilih bukan owner.');

        $dojang = Dojang::create($validated);
        $freePlan = SubscriptionPlan::where('code', 'free')->first();

        if ($freePlan) {
            $dojang->subscription()->create([
                'subscription_plan_id' => $freePlan->id,
                'started_at' => now()->toDateString(),
                'expired_at' => null,
                'status' => 'free',
            ]);
        }

        return redirect()
            ->route('dojangs.index')
            ->with('success', 'Dojang berhasil dibuat.');
    }

    public function show(Dojang $dojang)
    {
        $this->authorizeDojangAccess($dojang);

        $dojang->load([
            'owner:id,name,email',
            'rooms:id,dojang_id,name,registration_fee,monthly_fee,is_active',
        ]);

        return Inertia::render('Dojangs/Show', [
            'dojang' => $dojang,
        ]);
    }

    public function edit(Dojang $dojang)
    {
        $user = Auth::user();

        $this->authorizeDojangAccess($dojang);

        $owners = [];

        if ($user->hasRole('super_admin')) {
            $owners = User::role('owner')
                ->select('id', 'name', 'email')
                ->orderBy('name')
                ->get();
        }

        return Inertia::render('Dojangs/Edit', [
            'dojang' => $dojang,
            'owners' => $owners,
            'isSuperAdmin' => $user->hasRole('super_admin'),
        ]);
    }

    public function update(Request $request, Dojang $dojang)
    {
        $user = Auth::user();

        $this->authorizeDojangAccess($dojang);

        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'address' => ['nullable', 'string'],
            'domain' => ['required', 'string', 'max:255', 'unique:dojangs,domain,' . $dojang->id],
            'phone' => ['nullable', 'string', 'max:50'],
            'is_active' => ['required', 'boolean'],
        ];

        if ($user->hasRole('super_admin')) {
            $rules['user_id'] = ['required', 'exists:users,id'];
        }

        $validated = $request->validate($rules);

        if ($user->hasRole('super_admin')) {
            $owner = User::findOrFail($validated['user_id']);

            abort_unless($owner->hasRole('owner'), 422, 'User yang dipilih bukan owner.');
        } else {
            unset($validated['user_id']);
        }

        $dojang->update($validated);

        return redirect()
            ->route('dojangs.index')
            ->with('success', 'Dojang berhasil diperbarui.');
    }

    public function destroy(Dojang $dojang)
    {
        $user = Auth::user();

        abort_unless($user->hasRole('super_admin'), 403);

        $dojang->delete();

        return redirect()
            ->route('dojangs.index')
            ->with('success', 'Dojang berhasil dihapus.');
    }

    private function authorizeDojangAccess(Dojang $dojang): void
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return;
        }

        if ($user->hasRole('owner') && $dojang->user_id === $user->id) {
            return;
        }

        abort(403);
    }
}