<?php

namespace App\Http\Controllers;

use App\Models\Dojang;
use App\Models\DojangPayment;
use App\Models\SubscriptionPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DojangPaymentController extends Controller
{
    public function index()
    {
        $payments = DojangPayment::query()
            ->with(['dojang.owner', 'plan'])
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('DojangPayments/Index', [
            'payments' => $payments,
        ]);
    }

    public function show(DojangPayment $dojangPayment)
    {
        $dojangPayment->load(['dojang.owner', 'plan']);

        return Inertia::render('DojangPayments/Show', [
            'payment' => $dojangPayment,
        ]);
    }

    public function generateMonthly(Request $request)
    {
        $validated = $request->validate([
            'period' => ['required', 'date_format:Y-m'],
            'due_day' => ['required', 'integer', 'min:1', 'max:28'],
            'overdue_day' => ['required', 'integer', 'min:1', 'max:28', 'gte:due_day'],
        ]);

        $periodStart = Carbon::createFromFormat('Y-m', $validated['period'])
            ->startOfMonth();

        $periodEnd = Carbon::createFromFormat('Y-m', $validated['period'])
            ->endOfMonth();

        $dueDate = Carbon::createFromFormat('Y-m', $validated['period'])
            ->day($validated['due_day']);

        $overdueAt = Carbon::createFromFormat('Y-m', $validated['period'])
            ->day($validated['overdue_day']);

        $createdCount = 0;
        $skippedCount = 0;

        $dojangs = Dojang::query()
            ->with(['subscription.plan'])
            ->where('is_active', true)
            ->get();

        foreach ($dojangs as $dojang) {
            $subscription = $dojang->subscription;
            $plan = $subscription?->plan;

            if (! $subscription || ! $plan) {
                $skippedCount++;
                continue;
            }

            if ($plan->code === 'free' || $plan->price <= 0) {
                $skippedCount++;
                continue;
            }

            $alreadyExists = DojangPayment::query()
                ->where('dojang_id', $dojang->id)
                ->whereDate('period_start', $periodStart)
                ->whereDate('period_end', $periodEnd)
                ->exists();

            if ($alreadyExists) {
                $skippedCount++;
                continue;
            }

            DojangPayment::create([
                'dojang_id' => $dojang->id,
                'subscription_plan_id' => $plan->id,
                'period_start' => $periodStart->toDateString(),
                'period_end' => $periodEnd->toDateString(),
                'due_date' => $dueDate->toDateString(),
                'overdue_at' => $overdueAt->toDateString(),
                'amount' => $plan->price,
                'status' => 'pending',
                'invoice_number' => $this->generateInvoiceNumber($dojang->id, $periodStart),
                'note' => 'Tagihan subscription plan ' . $plan->name,
            ]);

            $createdCount++;
        }

        return back()->with(
            'success',
            "Generate selesai. {$createdCount} tagihan dibuat, {$skippedCount} dilewati."
        );
    }

    public function markAsPaid(Request $request, DojangPayment $dojangPayment)
    {
        $validated = $request->validate([
            'payment_method' => ['nullable', 'string', 'max:100'],
            'note' => ['nullable', 'string'],
        ]);

        if ($dojangPayment->status === 'paid') {
            return back()->with('error', 'Tagihan ini sudah dibayar.');
        }

        $dojangPayment->load(['dojang.subscription', 'plan']);

        $dojangPayment->update([
            'status' => 'paid',
            'payment_method' => $validated['payment_method'] ?? 'manual',
            'paid_at' => now(),
            'note' => $validated['note'] ?? $dojangPayment->note,
        ]);

        $dojangPayment->dojang->subscription()->updateOrCreate(
            [
                'dojang_id' => $dojangPayment->dojang_id,
            ],
            [
                'subscription_plan_id' => $dojangPayment->subscription_plan_id,
                'started_at' => $dojangPayment->period_start,
                'expired_at' => $dojangPayment->period_end,
                'status' => 'active',
            ]
        );

        return back()->with('success', 'Tagihan berhasil ditandai lunas dan subscription diperpanjang.');
    }

    public function markAsFailed(DojangPayment $dojangPayment)
    {
        if ($dojangPayment->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah paid tidak bisa ditandai failed.');
        }

        $dojangPayment->update([
            'status' => 'failed',
        ]);

        return back()->with('success', 'Tagihan berhasil ditandai failed.');
    }

    public function cancel(DojangPayment $dojangPayment)
    {
        if ($dojangPayment->status === 'paid') {
            return back()->with('error', 'Tagihan yang sudah paid tidak bisa dibatalkan.');
        }

        $dojangPayment->update([
            'status' => 'cancelled',
        ]);

        return back()->with('success', 'Tagihan berhasil dibatalkan.');
    }

    private function generateInvoiceNumber(int $dojangId, Carbon $periodStart): string
    {
        return 'DJP-' .
            $periodStart->format('Ym') .
            '-' .
            str_pad((string) $dojangId, 5, '0', STR_PAD_LEFT) .
            '-' .
            strtoupper(str()->random(5));
    }
}
