<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            /*
            |--------------------------------------------------------------------------
            | Core Access
            |--------------------------------------------------------------------------
            */
            RolePermissionSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | SaaS Master Data
            |--------------------------------------------------------------------------
            */
            FeatureSeeder::class,
            SubscriptionPlanSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Demo Users & Dojang
            |--------------------------------------------------------------------------
            */
            UserRoleSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Demo Operational Data
            |--------------------------------------------------------------------------
            */
            AttendanceSimulationSeeder::class,

            /*
            |--------------------------------------------------------------------------
            | Default Subscription for Existing Dojangs
            |--------------------------------------------------------------------------
            */
            DojangSubscriptionSeeder::class,
        ]);
    }
}
