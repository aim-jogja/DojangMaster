<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        $permissions = [
            // Dojang
            'manage dojangs',
            'view dojangs',

            // Room / Class
            'manage rooms',
            'view rooms',

            // Schedule
            'manage schedules',
            'view schedules',

            // User Management
            'manage students',
            'view students',
            'manage trainers',
            'view trainers',

            // Attendance
            'manage attendances',
            'view attendances',

            // Student Payment
            'manage student payments',
            'view student payments',

            // Dojang Payment / SaaS Payment
            'manage dojang payments',
            'view dojang payments',

            // Role & Permission
            'manage roles',

            // Student Payment
            'view payment categories',
            'create payment categories',
            'edit payment categories',
            'delete payment categories',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        $superAdmin = Role::firstOrCreate([
            'name' => 'super_admin',
            'guard_name' => 'web',
        ]);

        $owner = Role::firstOrCreate([
            'name' => 'owner',
            'guard_name' => 'web',
        ]);

        $trainer = Role::firstOrCreate([
            'name' => 'trainer',
            'guard_name' => 'web',
        ]);

        $student = Role::firstOrCreate([
            'name' => 'student',
            'guard_name' => 'web',
        ]);

        /**
         * Super Admin
         * Pemilik platform SaaS.
         * Boleh mengelola semua data.
         */
        $superAdmin->syncPermissions($permissions);

        /**
         * Owner
         * Pemilik dojang.
         * Boleh mengelola dojang miliknya, room, jadwal, pelatih, siswa, absensi, dan pembayaran siswa.
         */
        $owner->syncPermissions([
            'manage dojangs',
            'view dojangs',

            'manage rooms',
            'view rooms',

            'manage schedules',
            'view schedules',

            'manage students',
            'view students',

            'manage trainers',
            'view trainers',

            'manage attendances',
            'view attendances',

            'manage student payments',
            'view student payments',

            'view dojang payments',
        ]);

        /**
         * Trainer
         * Pelatih.
         * Fokus ke jadwal, siswa, dan absensi.
         */
        $trainer->syncPermissions([
            'view dojangs',

            'view rooms',
            'view schedules',

            'view students',

            'manage attendances',
            'view attendances',

            'view student payments',
        ]);

        /**
         * Student
         * Murid.
         * Hanya melihat jadwal, absensi pribadi, dan pembayaran pribadi.
         */
        $student->syncPermissions([
            'view rooms',
            'view schedules',
            'view attendances',
            'view student payments',
        ]);
    }
}