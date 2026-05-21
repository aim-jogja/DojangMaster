<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ScheduleController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $schedules = Schedule::query()
            ->with([
                'room:id,dojang_id,name',
                'room.dojang:id,user_id,name',
            ])
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->whereHas('room.dojang', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Schedules/Index', [
            'schedules' => $schedules,
        ]);
    }

    public function create()
    {
        $rooms = $this->availableRoomsForUser();

        return Inertia::render('Schedules/Create', [
            'rooms' => $rooms,
            'days' => $this->days(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'day' => ['required', 'string', 'max:50'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $this->authorizeRoomAccess((int) $validated['room_id']);

        Schedule::create($validated);

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Schedule berhasil dibuat.');
    }

    public function show(Schedule $schedule)
    {
        $this->authorizeScheduleAccess($schedule);

        $schedule->load([
            'room:id,dojang_id,name,description',
            'room.dojang:id,user_id,name',
            'attendances:id,schedule_id,user_id,date,status,note',
            'attendances.student:id,name,email',
        ]);

        return Inertia::render('Schedules/Show', [
            'schedule' => $schedule,
        ]);
    }

    public function edit(Schedule $schedule)
    {
        $this->authorizeScheduleAccess($schedule);

        $rooms = $this->availableRoomsForUser();

        return Inertia::render('Schedules/Edit', [
            'schedule' => $schedule,
            'rooms' => $rooms,
            'days' => $this->days(),
        ]);
    }

    public function update(Request $request, Schedule $schedule)
    {
        $this->authorizeScheduleAccess($schedule);

        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
            'day' => ['required', 'string', 'max:50'],
            'start_time' => ['required', 'date_format:H:i'],
            'end_time' => ['required', 'date_format:H:i', 'after:start_time'],
        ]);

        $this->authorizeRoomAccess((int) $validated['room_id']);

        $schedule->update($validated);

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Schedule berhasil diperbarui.');
    }

    public function destroy(Schedule $schedule)
    {
        $this->authorizeScheduleAccess($schedule);

        $schedule->delete();

        return redirect()
            ->route('schedules.index')
            ->with('success', 'Schedule berhasil dihapus.');
    }

    private function availableRoomsForUser()
    {
        $user = Auth::user();

        return Room::query()
            ->select('id', 'dojang_id', 'name')
            ->with('dojang:id,user_id,name')
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->whereHas('dojang', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->orderBy('name')
            ->get();
    }

    private function authorizeScheduleAccess(Schedule $schedule): void
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return;
        }

        $schedule->loadMissing('room.dojang');

        if ($user->hasRole('owner') && $schedule->room->dojang->user_id === $user->id) {
            return;
        }

        abort(403);
    }

    private function authorizeRoomAccess(int $roomId): void
    {
        $user = Auth::user();

        if ($user->hasRole('super_admin')) {
            return;
        }

        $isOwnerRoom = Room::query()
            ->where('id', $roomId)
            ->whereHas('dojang', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->exists();

        abort_unless($isOwnerRoom, 403);
    }

    private function days(): array
    {
        return [
            'Monday' => 'Senin',
            'Tuesday' => 'Selasa',
            'Wednesday' => 'Rabu',
            'Thursday' => 'Kamis',
            'Friday' => 'Jumat',
            'Saturday' => 'Sabtu',
            'Sunday' => 'Minggu',
        ];
    }
}