<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class TrainerController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $trainers = User::role('trainer')
            ->with('dojang:id,name,user_id')
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->whereHas('dojang', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Trainers/Index', [
            'trainers' => $trainers,
        ]);
    }

    public function create()
    {
        return Inertia::render('Trainers/Create', [
            'dojangs' => $this->availableDojangsForUser(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        $this->authorizeDojangAccess((int) $validated['dojang_id']);

        $trainer = User::create([
            'dojang_id' => $validated['dojang_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $trainer->assignRole('trainer');

        return redirect()
            ->route('trainers.index')
            ->with('success', 'Trainer berhasil dibuat.');
    }

    public function show(User $trainer)
    {
        $this->authorizeTrainerAccess($trainer);

        $trainer->load('dojang:id,name,user_id');

        return Inertia::render('Trainers/Show', [
            'trainer' => $trainer,
        ]);
    }

    public function edit(User $trainer)
    {
        $this->authorizeTrainerAccess($trainer);

        return Inertia::render('Trainers/Edit', [
            'trainer' => $trainer,
            'dojangs' => $this->availableDojangsForUser(),
        ]);
    }

    public function update(Request $request, User $trainer)
    {
        $this->authorizeTrainerAccess($trainer);

        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($trainer->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $this->authorizeDojangAccess((int) $validated['dojang_id']);

        $data = [
            'dojang_id' => $validated['dojang_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $trainer->update($data);
        $trainer->syncRoles(['trainer']);

        return redirect()
            ->route('trainers.index')
            ->with('success', 'Trainer berhasil diperbarui.');
    }

    public function destroy(User $trainer)
    {
        $this->authorizeTrainerAccess($trainer);

        $trainer->delete();

        return redirect()
            ->route('trainers.index')
            ->with('success', 'Trainer berhasil dihapus.');
    }

    private function availableDojangsForUser()
    {
        $user = Auth::user();

        return Dojang::query()
            ->select('id', 'name', 'user_id')
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('name')
            ->get();
    }

    private function authorizeDojangAccess(int $dojangId): void
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return;
        }

        $allowed = Dojang::where('id', $dojangId)
            ->where('user_id', $user->id)
            ->exists();

        abort_unless($allowed, 403);
    }

    private function authorizeTrainerAccess(User $trainer): void
    {
        $user = Auth::user();

        abort_unless($trainer->hasRole('trainer'), 404);

        if ($user->hasRole('super_admin')) {
            return;
        }

        $trainer->loadMissing('dojang');

        if ($user->hasRole('owner') && $trainer->dojang?->user_id === $user->id) {
            return;
        }

        abort(403);
    }
}