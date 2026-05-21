<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Feature;

class FeatureSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (config('features') as $feature) {
            Feature::updateOrCreate(
                ['code' => $feature['code']],
                [
                    'name' => $feature['name'],
                    'description' => $feature['description'] ?? null,
                    'is_active' => $feature['is_active'] ?? true,
                ]
            );
        }
    }
}
