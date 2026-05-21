<?php

namespace Database\Seeders;

use App\Models\Feature;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'data' => [
                    'code' => 'free',
                    'name' => 'Free',
                    'description' => 'Paket gratis untuk dojang baru dengan fitur dasar.',
                    'price' => 0,
                    'max_trainers' => 1,
                    'max_students' => 20,
                    'max_rooms' => 1,
                    'is_active' => true,
                ],
                'features' => [
                    'attendance_qr',
                ],
            ],
            [
                'data' => [
                    'code' => 'starter',
                    'name' => 'Starter',
                    'description' => 'Paket awal untuk dojang kecil.',
                    'price' => 50000,
                    'max_trainers' => 3,
                    'max_students' => 50,
                    'max_rooms' => 3,
                    'is_active' => true,
                ],
                'features' => [
                    'attendance_qr',
                    'student_payment',
                ],
            ],
            [
                'data' => [
                    'code' => 'pro',
                    'name' => 'Pro',
                    'description' => 'Paket untuk dojang berkembang dengan fitur laporan.',
                    'price' => 150000,
                    'max_trainers' => 10,
                    'max_students' => 200,
                    'max_rooms' => 10,
                    'is_active' => true,
                ],
                'features' => [
                    'attendance_qr',
                    'student_payment',
                    'reports',
                ],
            ],
            [
                'data' => [
                    'code' => 'premium',
                    'name' => 'Premium',
                    'description' => 'Paket lengkap untuk dojang besar.',
                    'price' => 300000,
                    'max_trainers' => null,
                    'max_students' => null,
                    'max_rooms' => null,
                    'is_active' => true,
                ],
                'features' => [
                    'attendance_qr',
                    'student_payment',
                    'reports',
                    'export_reports',
                    'custom_domain',
                    'whatsapp_remainder',
                ],
            ],
        ];

        foreach ($plans as $item) {
            $plan = SubscriptionPlan::updateOrCreate(
                ['code' => $item['data']['code']],
                $item['data']
            );

            $featureIds = Feature::whereIn('code', $item['features'])
                ->pluck('id')
                ->toArray();

            $plan->features()->sync($featureIds);
        }
    }
}