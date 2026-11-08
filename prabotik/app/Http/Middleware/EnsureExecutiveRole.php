<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureExecutiveRole
{
    /**
     * Handle an incoming request.
     * Memastikan hanya Kepala Puskesmas (role: executive) yang dapat mengakses.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (session('auth_role') !== 'executive') {
            return redirect()->route('gateway')
                ->with('error', '🔒 Akses Ditolak! Portal Kepala Puskesmas dilindungi kata sandi pimpinan faskes. Silakan masukkan password resmi dr. Bambang Utoyo, M.M.');
        }

        return $next($request);
    }
}
