<?php

namespace App\Http\Controllers;

use App\Models\DojangPayment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class OwnerBillingController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $dojangs = $user->ownedDojangs()
            ->with([
                'subscription.plan.features',
            ])
            ->get();

        if ($dojangs->isEmpty()) {
            abort(403, 'Akun owner belum memiliki dojang.');
        }

        $dojangIds = $dojangs->pluck('id');

        $payments = DojangPayment::query()
            ->with(['dojang', 'plan'])
            ->whereIn('dojang_id', $dojangIds)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return Inertia::render('Owner/Billing/Index', [
            'dojangs' => $dojangs,
            'payments' => $payments,
        ]);
    }

    public function show(Request $request, DojangPayment $dojangPayment)
    {
        $user = $request->user();

        $ownedDojangIds = $user->ownedDojangs()
            ->pluck('id');

        if ($ownedDojangIds->isEmpty()) {
            abort(403, 'Akun owner belum memiliki dojang.');
        }

        if (! $ownedDojangIds->contains($dojangPayment->dojang_id)) {
            abort(403, 'Anda tidak memiliki akses ke tagihan ini.');
        }

        $dojangPayment->load([
            'dojang',
            'plan',
        ]);

        return Inertia::render('Owner/Billing/Show', [
            'payment' => $dojangPayment,
            'dojang' => $dojangPayment->dojang,
        ]);
    }
}
