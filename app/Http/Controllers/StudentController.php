<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\Room;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Illuminate\Support\Str;

class StudentController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $students = User::role('student')
            ->with(['dojang:id,name,user_id', 'rooms:id,name'])
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->whereHas('dojang', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Students/Index', [
            'students' => $students,
        ]);
    }

    public function create()
    {
        return Inertia::render('Students/Create', [
            'dojangs' => $this->availableDojangsForUser(),
            'rooms' => $this->availableRoomsForUser(),
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'room_ids' => ['nullable', 'array'],
            'room_ids.*' => ['exists:rooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
            'qr_token' => (string) Str::uuid()
        ]);

        $this->authorizeDojangAccess((int) $validated['dojang_id']);

        $roomIds = $validated['room_ids'] ?? [];
        $this->authorizeRoomsAccess($roomIds, (int) $validated['dojang_id']);

        $student = User::create([
            'dojang_id' => $validated['dojang_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
        ]);

        $student->assignRole('student');

        if (!empty($roomIds)) {
            $syncData = collect($roomIds)->mapWithKeys(function ($roomId) {
                return [
                    $roomId => [
                        'joined_at' => now()->toDateString(),
                        'is_active' => true,
                    ],
                ];
            })->toArray();

            $student->rooms()->syncWithoutDetaching($syncData);
        }

        return redirect()
            ->route('students.index')
            ->with('success', 'Student berhasil dibuat.');
    }

    public function show(User $student)
    {
        $this->authorizeStudentAccess($student);
        if (!$student->qr_token) {
            $student->update([
                'qr_token' => (string) Str::uuid(),
            ]);
        
            $student->refresh();
        }

        $student->load([
            'dojang:id,name,user_id',
            'rooms:id,dojang_id,name,registration_fee,monthly_fee',
            'rooms.dojang:id,name,user_id',
            'attendances.schedule.room:id,name',
            'studentPayments.room:id,name',
        ]);

        $availableRooms = $this->availableRoomsForUser()
            ->where('dojang_id', $student->dojang_id)
            ->values();

        return Inertia::render('Students/Show', [
            'student' => $student,
            'availableRooms' => $availableRooms,
        ]);
    }

    public function edit(User $student)
    {
        $this->authorizeStudentAccess($student);

        $student->load('rooms:id');

        return Inertia::render('Students/Edit', [
            'student' => $student,
            'dojangs' => $this->availableDojangsForUser(),
            'rooms' => $this->availableRoomsForUser(),
            'selectedRoomIds' => $student->rooms->pluck('id'),
        ]);
    }

    public function update(Request $request, User $student)
    {
        $this->authorizeStudentAccess($student);

        $validated = $request->validate([
            'dojang_id' => ['required', 'exists:dojangs,id'],
            'room_ids' => ['nullable', 'array'],
            'room_ids.*' => ['exists:rooms,id'],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($student->id),
            ],
            'password' => ['nullable', 'string', 'min:8'],
        ]);

        $this->authorizeDojangAccess((int) $validated['dojang_id']);

        $roomIds = $validated['room_ids'] ?? [];
        $this->authorizeRoomsAccess($roomIds, (int) $validated['dojang_id']);

        $data = [
            'dojang_id' => $validated['dojang_id'],
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        if (!empty($validated['password'])) {
            $data['password'] = Hash::make($validated['password']);
        }

        $student->update($data);
        $student->syncRoles(['student']);

        $syncData = collect($roomIds)->mapWithKeys(function ($roomId) {
            return [
                $roomId => [
                    'joined_at' => now()->toDateString(),
                    'is_active' => true,
                ],
            ];
        })->toArray();

        $student->rooms()->sync($syncData);

        return redirect()
            ->route('students.index')
            ->with('success', 'Student berhasil diperbarui.');
    }

    public function destroy(User $student)
    {
        $this->authorizeStudentAccess($student);

        $student->rooms()->detach();
        $student->delete();

        return redirect()
            ->route('students.index')
            ->with('success', 'Student berhasil dihapus.');
    }

    public function attachRoom(Request $request, User $student)
    {
        $this->authorizeStudentAccess($student);

        $validated = $request->validate([
            'room_id' => ['required', 'exists:rooms,id'],
        ]);

        $room = Room::findOrFail($validated['room_id']);

        abort_unless($room->dojang_id === $student->dojang_id, 422, 'Room tidak sesuai dengan dojang student.');

        $this->authorizeRoomAccess($room);

        $student->rooms()->syncWithoutDetaching([
            $room->id => [
                'joined_at' => now()->toDateString(),
                'is_active' => true,
            ],
        ]);

        return back()->with('success', 'Student berhasil dimasukkan ke room.');
    }

    public function detachRoom(User $student, Room $room)
    {
        $this->authorizeStudentAccess($student);
        $this->authorizeRoomAccess($room);

        $student->rooms()->detach($room->id);

        return back()->with('success', 'Student berhasil dikeluarkan dari room.');
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

    private function availableRoomsForUser()
    {
        $user = Auth::user();

        return Room::query()
            ->select('id', 'dojang_id', 'name')
            ->with('dojang:id,name,user_id')
            ->when($user->hasRole('owner'), function ($query) use ($user) {
                $query->whereHas('dojang', function ($q) use ($user) {
                    $q->where('user_id', $user->id);
                });
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

    private function authorizeRoomsAccess(array $roomIds, int $dojangId): void
    {
        if (empty($roomIds)) {
            return;
        }

        $count = Room::whereIn('id', $roomIds)
            ->where('dojang_id', $dojangId)
            ->count();

        abort_unless($count === count($roomIds), 422, 'Ada room yang tidak sesuai dengan dojang student.');

        foreach ($roomIds as $roomId) {
            $room = Room::findOrFail($roomId);
            $this->authorizeRoomAccess($room);
        }
    }

    private function authorizeStudentAccess(User $student): void
    {
        $user = Auth::user();

        abort_unless($student->hasRole('student'), 404);

        if ($user->hasRole('super_admin')) {
            return;
        }

        $student->loadMissing('dojang');

        if ($user->hasRole('owner') && $student->dojang?->user_id === $user->id) {
            return;
        }

        abort(403);
    }
}