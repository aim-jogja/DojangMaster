<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class RoomController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $rooms = Room::query()
            ->with('dojang:id,name,user_id')
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->whereHas('dojang', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Rooms/Index', [
            'rooms' => $rooms,
        ]);
    }

    public function create()
    {
        $user = Auth::user();

        $dojangs = $this->availableDojangsForUser();

        return Inertia::render('Rooms/Create', [
            'dojangs' => $dojangs,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'registration_fee' => ['required', 'numeric', 'min:0'],
            'monthly_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $this->authorizeDojangAccess((int) $validated['dojang_id']);

        Room::create($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Room berhasil dibuat.');
    }

    public function show(Room $room)
    {
        $this->authorizeRoomAccess($room);

        $room->load([
            'dojang:id,name,user_id',
            'schedules:id,room_id,day,start_time,end_time',
            'students:id,name,email,dojang_id',
        ]);

        return Inertia::render('Rooms/Show', [
            'room' => $room,
        ]);
    }

    public function edit(Room $room)
    {
        $this->authorizeRoomAccess($room);

        $dojangs = $this->availableDojangsForUser();

        return Inertia::render('Rooms/Edit', [
            'room' => $room,
            'dojangs' => $dojangs,
        ]);
    }

    public function update(Request $request, Room $room)
    {
        $this->authorizeRoomAccess($room);

        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'registration_fee' => ['required', 'numeric', 'min:0'],
            'monthly_fee' => ['required', 'numeric', 'min:0'],
            'is_active' => ['required', 'boolean'],
        ]);

        $this->authorizeDojangAccess((int) $validated['dojang_id']);

        $room->update($validated);

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Room berhasil diperbarui.');
    }

    public function destroy(Room $room)
    {
        $this->authorizeRoomAccess($room);

        $room->delete();

        return redirect()
            ->route('rooms.index')
            ->with('success', 'Room berhasil dihapus.');
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

    private function authorizeRoomAccess(Room $room): void
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return;
        }

        $room->loadMissing('dojang');

        if ($user->hasRole('owner') && $room->dojang->user_id === $user->id) {
            return;
        }

        abort(403);
    }

    private function authorizeDojangAccess(int $dojangId): void
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return;
        }

        $isOwnerDojang = Dojang::query()
            ->where('id', $dojangId)
            ->where('user_id', $user->id)
            ->exists();

        abort_unless($isOwnerDojang, 403);
    }
}