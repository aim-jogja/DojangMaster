<?php

namespace App\Http\Controllers;

use App\Models\Feature;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class SubscriptionPlanController extends Controller
{
    public function index()
    {
        $plans = SubscriptionPlan::query()
            ->with('features')
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('SubscriptionPlans/Index', [
            'plans' => $plans,
        ]);
    }

    public function create()
    {
        $features = Feature::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('SubscriptionPlans/Create', [
            'features' => $features,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                'unique:subscription_plans,code',
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'max_trainers' => ['nullable', 'integer', 'min:0'],
            'max_students' => ['nullable', 'integer', 'min:0'],
            'max_rooms' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'feature_ids' => ['nullable', 'array'],
            'feature_ids.*' => ['exists:features,id'],
        ]);

        $featureIds = $validated['feature_ids'] ?? [];

        unset($validated['feature_ids']);

        $plan = SubscriptionPlan::create($validated);

        $plan->features()->sync($featureIds);

        return redirect()
            ->route('subscription-plans.index')
            ->with('success', 'Subscription plan berhasil ditambahkan.');
    }

    public function show(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->load('features');

        return Inertia::render('SubscriptionPlans/Show', [
            'plan' => $subscriptionPlan,
        ]);
    }

    public function edit(SubscriptionPlan $subscriptionPlan)
    {
        $subscriptionPlan->load('features');

        $features = Feature::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get();

        return Inertia::render('SubscriptionPlans/Edit', [
            'plan' => $subscriptionPlan,
            'features' => $features,
            'selectedFeatureIds' => $subscriptionPlan->features
                ->pluck('id')
                ->toArray(),
        ]);
    }

    public function update(Request $request, SubscriptionPlan $subscriptionPlan)
    {
        $validated = $request->validate([
            'code' => [
                'required',
                'string',
                'max:100',
                'alpha_dash',
                Rule::unique('subscription_plans', 'code')
                    ->ignore($subscriptionPlan->id),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'max_trainers' => ['nullable', 'integer', 'min:0'],
            'max_students' => ['nullable', 'integer', 'min:0'],
            'max_rooms' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['required', 'boolean'],
            'feature_ids' => ['nullable', 'array'],
            'feature_ids.*' => ['exists:features,id'],
        ]);

        $featureIds = $validated['feature_ids'] ?? [];

        unset($validated['feature_ids']);

        $subscriptionPlan->update($validated);

        $subscriptionPlan->features()->sync($featureIds);

        return redirect()
            ->route('subscription-plans.index')
            ->with('success', 'Subscription plan berhasil diperbarui.');
    }

    public function destroy(SubscriptionPlan $subscriptionPlan)
    {
        if (in_array($subscriptionPlan->code, ['free', 'starter', 'pro', 'premium'])) {
            return back()->with('error', 'Plan bawaan sistem tidak boleh dihapus.');
        }

        $subscriptionPlan->delete();

        return redirect()
            ->route('subscription-plans.index')
            ->with('success', 'Subscription plan berhasil dihapus.');
    }
}