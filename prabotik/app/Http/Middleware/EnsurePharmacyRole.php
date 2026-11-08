<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsurePharmacyRole
{
    /**
     * Handle an incoming request.
     * Memastikan hanya Petugas Farmasi (role: pharmacy) yang dapat mengakses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('auth_role') !== 'pharmacy') {
            return redirect()->route('gateway')
                ->with('error', 'Silakan masuk ke Portal Farmasi terlebih dahulu.');
        }

        return $next($request);
    }
}
