<?php

namespace Database\Seeders;

use App\Models\Dojang;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = User::updateOrCreate(
            ['email' => 'superadmin@example.com'],
            [
                'name' => 'Super Admin',
                'password' => Hash::make('password'),
            ]
        );

        $superAdmin->syncRoles(['super_admin']);

        $owner = User::updateOrCreate(
            ['email' => 'owner@example.com'],
            [
                'name' => 'Owner Dojang',
                'password' => Hash::make('password'),
            ]
        );

        $owner->syncRoles(['owner']);

        $dojang = Dojang::updateOrCreate(
            ['domain' => 'dojang-demo'],
            [
                'user_id' => $owner->id,
                'name' => 'Dojang Demo',
                'address' => 'Yogyakarta',
                'phone' => '081234567890',
                'is_active' => true,
            ]
        );

        $trainer = User::updateOrCreate(
            ['email' => 'trainer@example.com'],
            [
                'dojang_id' => $dojang->id,
                'name' => 'Trainer Demo',
                'password' => Hash::make('password'),
            ]
        );

        $trainer->syncRoles(['trainer']);

        $student = User::updateOrCreate(
            ['email' => 'student@example.com'],
            [
                'dojang_id' => $dojang->id,
                'name' => 'Student Demo',
                'password' => Hash::make('password'),
            ]
        );

        if (! $student->qr_token) {
            $student->forceFill([
                'qr_token' => (string) Str::uuid(),
            ])->save();
        }

        $student->syncRoles(['student']);
    }
}
