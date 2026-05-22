<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

class RolePermissionSeeder extends Seeder
{
    public function run(): void
    {
        app(PermissionRegistrar::class)->forgetCachedPermissions();

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

            // Student Billing & Payment
            'manage student bills',
            'view student bills',
            'manage student payments',
            'view student payments',

            // Payment Category
            'view payment categories',
            'create payment categories',
            'edit payment categories',
            'delete payment categories',

            // Dojang Payment / SaaS Billing
            'manage dojang payments',
            'view dojang payments',

            // Feature
            'features.view',
            'features.create',
            'features.edit',
            'features.delete',

            // Subscription Plan
            'subscription_plans.view',
            'subscription_plans.create',
            'subscription_plans.edit',
            'subscription_plans.delete',

            // Role & Permission
            'manage roles',
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

        $superAdmin->syncPermissions($permissions);

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

            'manage student bills',
            'view student bills',
            'manage student payments',
            'view student payments',

            'view payment categories',
            'create payment categories',
            'edit payment categories',
            'delete payment categories',

            'view dojang payments',
        ]);

        $trainer->syncPermissions([
            'view dojangs',
            'view rooms',
            'view schedules',
            'view students',

            'manage attendances',
            'view attendances',

            'view student bills',
            'view student payments',
        ]);

        $student->syncPermissions([
            'view rooms',
            'view schedules',
            'view attendances',
            'view student bills',
            'view student payments',
        ]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
}
