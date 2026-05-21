<?php

namespace App\Http\Middleware;

use App\Models\Dojang;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDojangCanUseFeature
{
    public function handle(Request $request, Closure $next, string $featureCode): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(401);
        }

        $dojang = $this->resolveDojang($request);

        if (! $dojang) {
            abort(403, 'Dojang tidak ditemukan untuk akun ini.');
        }

        $dojang->loadMissing('subscription.plan.features');

        if (! $dojang->canUseFeature($featureCode)) {
            abort(403, 'Fitur ini tidak tersedia pada paket dojang Anda.');
        }

        return $next($request);
    }

    private function resolveDojang(Request $request): ?Dojang
    {
        $user = $request->user();

        /*
         * 1. Jika route punya parameter dojang, gunakan itu.
         * Contoh route:
         * /dojangs/{dojang}/reports
         */
        $routeDojang = $request->route('dojang');

        if ($routeDojang instanceof Dojang) {
            if ($user->hasRole('super_admin')) {
                return $routeDojang;
            }

            if ($user->hasRole('owner')) {
                return $user->ownedDojangs()
                    ->where('dojangs.id', $routeDojang->id)
                    ->first();
            }

            if ((int) $user->dojang_id === (int) $routeDojang->id) {
                return $routeDojang;
            }

            return null;
        }

        /*
         * 2. Jika user adalah trainer/student, biasanya punya dojang_id.
         */
        if ($user->dojang_id) {
            return $user->dojang()
                ->with('subscription.plan.features')
                ->first();
        }

        /*
         * 3. Jika user adalah owner dan hanya punya satu dojang,
         * ambil dojang tersebut.
         */
        if ($user->hasRole('owner')) {
            $ownedDojangs = $user->ownedDojangs()
                ->with('subscription.plan.features')
                ->get();

            if ($ownedDojangs->count() === 1) {
                return $ownedDojangs->first();
            }

            /*
             * Jika owner punya banyak dojang, middleware tidak bisa menebak
             * dojang mana yang sedang dipakai kecuali route membawa parameter dojang.
             */
            return null;
        }

        return null;
    }
}
