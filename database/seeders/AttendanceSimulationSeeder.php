<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Dojang;
use App\Models\Room;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class AttendanceSimulationSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {
            Role::firstOrCreate([
                'name' => 'student',
                'guard_name' => 'web',
            ]);

            Role::firstOrCreate([
                'name' => 'trainer',
                'guard_name' => 'web',
            ]);

            Role::firstOrCreate([
                'name' => 'owner',
                'guard_name' => 'web',
            ]);

            $owner = User::updateOrCreate(
                ['email' => 'owner-flow@example.com'],
                [
                    'name' => 'Owner Flow Demo',
                    'password' => Hash::make('password'),
                ]
            );

            $owner->syncRoles(['owner']);

            $dojang = Dojang::updateOrCreate(
                ['name' => 'Dojang Flow Demo'],
                [
                    'user_id' => $owner->id,
                    'address' => 'Jl. Demo Attendance',
                    'phone' => '081234567890',
                    'is_active' => true,
                ]
            );

            $trainer = User::updateOrCreate(
                ['email' => 'trainer-flow@example.com'],
                [
                    'name' => 'Trainer Flow Demo',
                    'password' => Hash::make('password'),
                    'dojang_id' => $dojang->id,
                ]
            );

            $trainer->syncRoles(['trainer']);

            $rooms = [
                [
                    'name' => 'Kelas Pemula',
                    'description' => 'Room untuk siswa baru.',
                    'schedules' => [
                        [
                            'day' => 'monday',
                            'start_time' => '16:00:00',
                            'end_time' => '17:30:00',
                        ],
                        [
                            'day' => 'wednesday',
                            'start_time' => '16:00:00',
                            'end_time' => '17:30:00',
                        ],
                    ],
                    'students' => [
                        ['name' => 'Alya Pemula', 'email' => 'alya.pemula@example.com', 'status' => 'present'],
                        ['name' => 'Bagas Pemula', 'email' => 'bagas.pemula@example.com', 'status' => 'permission'],
                        ['name' => 'Citra Pemula', 'email' => 'citra.pemula@example.com', 'status' => null],
                    ],
                ],
                [
                    'name' => 'Kelas Menengah',
                    'description' => 'Room untuk siswa tingkat menengah.',
                    'schedules' => [
                        [
                            'day' => 'tuesday',
                            'start_time' => '18:30:00',
                            'end_time' => '20:00:00',
                        ],
                        [
                            'day' => 'thursday',
                            'start_time' => '18:30:00',
                            'end_time' => '20:00:00',
                        ],
                    ],
                    'students' => [
                        ['name' => 'Dimas Menengah', 'email' => 'dimas.menengah@example.com', 'status' => 'present'],
                        ['name' => 'Eka Menengah', 'email' => 'eka.menengah@example.com', 'status' => 'sick'],
                        ['name' => 'Farhan Menengah', 'email' => 'farhan.menengah@example.com', 'status' => null],
                    ],
                ],
                [
                    'name' => 'Kelas Lanjutan',
                    'description' => 'Room untuk siswa tingkat lanjutan.',
                    'schedules' => [
                        [
                            'day' => 'friday',
                            'start_time' => '19:00:00',
                            'end_time' => '20:30:00',
                        ],
                        [
                            'day' => 'sunday',
                            'start_time' => '08:00:00',
                            'end_time' => '09:30:00',
                        ],
                    ],
                    'students' => [
                        ['name' => 'Gilang Lanjutan', 'email' => 'gilang.lanjutan@example.com', 'status' => 'present'],
                        ['name' => 'Hana Lanjutan', 'email' => 'hana.lanjutan@example.com', 'status' => 'absent'],
                        ['name' => 'Iqbal Lanjutan', 'email' => 'iqbal.lanjutan@example.com', 'status' => null],
                    ],
                ],
            ];

            $attendanceDate = now()->toDateString();

            foreach ($rooms as $roomData) {
                $room = Room::updateOrCreate(
                    [
                        'dojang_id' => $dojang->id,
                        'name' => $roomData['name'],
                    ],
                    [
                        'dojang_id' => $dojang->id,
                        'name' => $roomData['name'],
                        'description' => $roomData['description'],
                        'is_active' => true,
                    ]
                );

                foreach ($roomData['schedules'] as $scheduleData) {
                    $schedule = Schedule::updateOrCreate(
                        [
                            'room_id' => $room->id,
                            'day' => $scheduleData['day'],
                            'start_time' => $scheduleData['start_time'],
                            'end_time' => $scheduleData['end_time'],
                        ],
                        [
                            'room_id' => $room->id,
                            'day' => $scheduleData['day'],
                            'start_time' => $scheduleData['start_time'],
                            'end_time' => $scheduleData['end_time'],
                            // 'is_active' => true,
                        ]
                    );

                    foreach ($roomData['students'] as $studentData) {
                        $student = User::updateOrCreate(
                            ['email' => $studentData['email']],
                            [
                                'name' => $studentData['name'],
                                'password' => Hash::make('password'),
                                'dojang_id' => $dojang->id,
                                'qr_token' => Str::uuid()->toString(),
                            ]
                        );

                        $student->syncRoles(['student']);

                        /*
                        |--------------------------------------------------------------------------
                        | Attach student to room
                        |--------------------------------------------------------------------------
                        | Jika nama pivot Anda berbeda, sesuaikan bagian ini.
                        |--------------------------------------------------------------------------
                        */
                        DB::table('room_user')->updateOrInsert(
                            [
                                'room_id' => $room->id,
                                'user_id' => $student->id,
                            ],
                            [
                                'is_active' => true,
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]
                        );

                        /*
                        |--------------------------------------------------------------------------
                        | Attendance sample
                        |--------------------------------------------------------------------------
                        | Hanya schedule pertama di setiap room yang dibuatkan sample attendance.
                        | Siswa dengan status null sengaja tidak dibuatkan record attendance.
                        |--------------------------------------------------------------------------
                        */
                        if (
                            $scheduleData === $roomData['schedules'][0]
                            && $studentData['status'] !== null
                        ) {
                            Attendance::updateOrCreate(
                                [
                                    'schedule_id' => $schedule->id,
                                    'user_id' => $student->id,
                                    'date' => $attendanceDate,
                                ],
                                [
                                    'status' => $studentData['status'],
                                    'note' => 'Data simulasi attendance untuk flow room.',
                                ]
                            );
                        }
                    }
                }
            }
        });
    }
}