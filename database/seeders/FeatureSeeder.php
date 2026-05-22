<?php

namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        $features = config('features', []);

        foreach ($features as $feature) {
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
