<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DojangSubscriptionController extends Controller
{
    public function edit(Dojang $dojang)
    {
        $dojang->load([
            'owner',
            'subscription.plan',
        ]);

        $plans = SubscriptionPlan::query()
            ->where('is_active', true)
            ->orderBy('price')
            ->get();

        return Inertia::render('Dojangs/Subscription/Edit', [
            'dojang' => $dojang,
            'plans' => $plans,
        ]);
    }

    public function update(Request $request, Dojang $dojang)
    {
        $validated = $request->validate([
            'subscription_plan_id' => [
                'required',
                'exists:subscription_plans,id',
            ],
            'started_at' => [
                'nullable',
                'date',
            ],
            'expired_at' => [
                'nullable',
                'date',
                'after_or_equal:started_at',
            ],
            'status' => [
                'required',
                'in:active,free,overdue,cancelled,expired',
            ],
        ]);

        $plan = SubscriptionPlan::findOrFail($validated['subscription_plan_id']);

        $status = $plan->code === 'free'
            ? 'free'
            : $validated['status'];

        $expiredAt = $plan->code === 'free'
            ? null
            : $validated['expired_at'];

        $dojang->subscription()->updateOrCreate(
            [
                'dojang_id' => $dojang->id,
            ],
            [
                'subscription_plan_id' => $plan->id,
                'started_at' => $validated['started_at'] ?? now()->toDateString(),
                'expired_at' => $expiredAt,
                'status' => $status,
            ]
        );

        return redirect()
            ->route('dojangs.index')
            ->with('success', 'Paket dojang berhasil diperbarui.');
    }
}
