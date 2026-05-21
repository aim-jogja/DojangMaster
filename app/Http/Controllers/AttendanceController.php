<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $schedules = $this->availableSchedulesForUser();

        $query = Attendance::query()
            ->with([
                'student:id,name,email,dojang_id',
                'schedule:id,room_id,day,start_time,end_time',
                'schedule.room:id,dojang_id,name',
                'schedule.room.dojang:id,name,user_id',
            ])
            ->when($request->schedule_id, function ($query) use ($request) {
                $query->where('schedule_id', $request->schedule_id);
            })
            ->when($request->date, function ($query) use ($request) {
                $query->where('date', $request->date);
            })
            ->when(!$user->hasRole('super_admin'), function ($query) use ($user) {
                $query->whereHas('schedule.room.dojang', function ($q) use ($user) {
                    if ($user->hasRole('owner')) {
                        $q->where('user_id', $user->id);
                    }

                    if ($user->hasRole('trainer')) {
                        $q->where('id', $user->dojang_id);
                    }
                });
            });

        $attendances = $query
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Attendances/Index', [
            'attendances' => $attendances,
            'schedules' => $schedules,
            'filters' => [
                'schedule_id' => $request->schedule_id,
                'date' => $request->date,
            ],
        ]);
    }

    public function scan()
    {
        return Inertia::render('Attendances/Scan', [
            'schedules' => $this->availableSchedulesForUser(),
            'today' => now()->toDateString(),
        ]);
    }

    public function storeScan(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'date' => ['required', 'date'],
            'qr_token' => ['required', 'string'],
        ]);

        $schedule = Schedule::with('room.dojang')->findOrFail($validated['schedule_id']);

        $this->authorizeScheduleAccess($schedule);

        $student = User::role('student')
            ->where('qr_token', $validated['qr_token'])
            ->first();

        if (!$student) {
            return back()->with('error', 'QR Code tidak valid atau siswa tidak ditemukan.');
        }

        if ((int) $student->dojang_id !== (int) $schedule->room->dojang_id) {
            return back()->with('error', 'Siswa tidak berada di dojang yang sesuai.');
        }

        $isStudentInRoom = $student->rooms()
            ->where('rooms.id', $schedule->room_id)
            ->wherePivot('is_active', true)
            ->exists();

        if (!$isStudentInRoom) {
            return back()->with('error', 'Siswa belum terdaftar di room/kelas untuk schedule ini.');
        }

        $attendance = Attendance::updateOrCreate(
            [
                'schedule_id' => $schedule->id,
                'user_id' => $student->id,
                'date' => $validated['date'],
            ],
            [
                'status' => 'present',
                'note' => 'Scanned QR by ' . Auth::user()->name,
            ]
        );

        return back()->with('success', $student->name . ' berhasil diabsen hadir.');
    }

    public function show(Schedule $schedule, string $date)
    {
        $this->authorizeScheduleAccess($schedule);

        $schedule->load([
            'room:id,dojang_id,name',
            'room.dojang:id,name,user_id',
        ]);

        $attendanceByUserId = Attendance::query()
            ->where('schedule_id', $schedule->id)
            ->where('date', $date)
            ->get()
            ->keyBy('user_id');

        $students = User::role('student')
            ->select('id', 'name', 'email', 'dojang_id')
            ->where('dojang_id', $schedule->room->dojang_id)
            ->whereHas('rooms', function ($query) use ($schedule) {
                $query->where('rooms.id', $schedule->room_id)
                    ->where('room_user.is_active', true);
            })
            ->orderBy('name')
            ->get()
            ->map(function ($student) use ($attendanceByUserId) {
                $attendance = $attendanceByUserId->get($student->id);

                return [
                    'id' => $student->id,
                    'name' => $student->name,
                    'email' => $student->email,
                    'status' => $attendance?->status ?? 'absent',
                    'note' => $attendance?->note,
                    'attendance_id' => $attendance?->id,
                    'updated_at' => $attendance?->updated_at?->format('Y-m-d H:i:s'),
                ];
            });

        return Inertia::render('Attendances/Show', [
            'schedule' => $schedule,
            'date' => $date,
            'students' => $students,
            'statuses' => [
                'present' => 'Hadir',
                'permission' => 'Izin',
                'sick' => 'Sakit',
                'absent' => 'Tidak Hadir',
            ],
        ]);
    }

    public function storeManual(Request $request)
    {
        $validated = $request->validate([
            'schedule_id' => ['required', 'exists:schedules,id'],
            'user_id' => ['required', 'exists:users,id'],
            'date' => ['required', 'date'],
            'status' => ['required', 'in:present,permission,sick,absent'],
            'note' => ['nullable', 'string', 'max:255'],
        ]);

        $schedule = Schedule::with('room.dojang')->findOrFail($validated['schedule_id']);

        $this->authorizeScheduleAccess($schedule);

        $student = User::role('student')->findOrFail($validated['user_id']);

        if ((int) $student->dojang_id !== (int) $schedule->room->dojang_id) {
            return back()->with('error', 'Siswa tidak berada di dojang yang sesuai.');
        }

        $isStudentInRoom = $student->rooms()
            ->where('rooms.id', $schedule->room_id)
            ->wherePivot('is_active', true)
            ->exists();

        if (!$isStudentInRoom) {
            return back()->with('error', 'Siswa belum terdaftar di room/kelas untuk schedule ini.');
        }

        Attendance::updateOrCreate(
            [
                'schedule_id' => $schedule->id,
                'user_id' => $student->id,
                'date' => $validated['date'],
            ],
            [
                'status' => $validated['status'],
                'note' => $validated['note'] ?? 'Manual attendance by ' . Auth::user()->name,
            ]
        );

        return back()->with('success', 'Attendance ' . $student->name . ' berhasil diperbarui.');
    }

    private function availableSchedulesForUser()
    {
        $user = Auth::user();

        return Schedule::query()
            ->select('id', 'room_id', 'day', 'start_time', 'end_time')
            ->with([
                'room:id,dojang_id,name',
                'room.dojang:id,name,user_id',
            ])
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->whereHas('room.dojang', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->when($user->hasRole('trainer'), function ($query) use ($user) {
                $query->whereHas('room.dojang', function ($q) use ($user) {
                    $q->where('id', $user->dojang_id);
                });
            })
            ->orderBy('room_id')
            ->orderBy('day')
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

        if ($user->hasRole('trainer') && $schedule->room->dojang_id === $user->dojang_id) {
            return;
        }

        abort(403);
    }
}