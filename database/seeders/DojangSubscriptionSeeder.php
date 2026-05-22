<?php

namespace Database\Seeders;

use App\Models\Dojang;
use App\Models\DojangSubscription;
use App\Models\SubscriptionPlan;
use Illuminate\Database\Seeder;

class DojangSubscriptionSeeder extends Seeder
{
    public function run(): void
    {
        $freePlan = SubscriptionPlan::query()
            ->where('code', 'free')
            ->first();

        if (! $freePlan) {
            $this->command?->warn('Plan free belum ditemukan. Jalankan SubscriptionPlanSeeder terlebih dahulu.');
            return;
        }

        Dojang::query()
            ->whereDoesntHave('subscription')
            ->chunkById(100, function ($dojangs) use ($freePlan) {
                foreach ($dojangs as $dojang) {
                    DojangSubscription::updateOrCreate(
                        ['dojang_id' => $dojang->id],
                        [
                            'subscription_plan_id' => $freePlan->id,
                            'started_at' => now()->toDateString(),
                            'expired_at' => null,
                            'status' => 'free',
                        ]
                    );
                }
            });
    }
}
