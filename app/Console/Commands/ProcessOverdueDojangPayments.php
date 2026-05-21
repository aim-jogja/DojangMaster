<?php

namespace App\Console\Commands;

use App\Models\DojangPayment;
use App\Models\SubscriptionPlan;
use Illuminate\Console\Command;

class ProcessOverdueDojangPayments extends Command
{
    protected $signature = 'dojang-payments:process-overdue';

    protected $description = 'Process overdue dojang payments and downgrade expired subscriptions to free plan';

    public function handle(): int
    {
        $today = now()->toDateString();

        DojangPayment::query()
            ->where('status', 'pending')
            ->whereDate('due_date', '<', $today)
            ->update([
                'status' => 'overdue',
            ]);

        $freePlan = SubscriptionPlan::where('code', 'free')->first();

        if (! $freePlan) {
            $this->error('Free plan tidak ditemukan.');
            return self::FAILURE;
        }

        $expiredPayments = DojangPayment::query()
            ->with('dojang.subscription')
            ->whereIn('status', ['pending', 'overdue'])
            ->whereDate('overdue_at', '<', $today)
            ->get();

        foreach ($expiredPayments as $payment) {
            $payment->update([
                'status' => 'expired',
            ]);

            $payment->dojang->subscription()->updateOrCreate(
                [
                    'dojang_id' => $payment->dojang_id,
                ],
                [
                    'subscription_plan_id' => $freePlan->id,
                    'started_at' => now()->toDateString(),
                    'expired_at' => null,
                    'status' => 'free',
                ]
            );
        }

        $this->info('Overdue dojang payments berhasil diproses.');

        return self::SUCCESS;
    }
}
