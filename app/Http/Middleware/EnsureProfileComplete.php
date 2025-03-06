<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsureProfileComplete
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();

            // Cek apakah ada data penting yang masih null
            if (is_null($user->provinsi) || is_null($user->kab_kota)) {
                return redirect()->route('profile.complete')->with('error', 'Silakan lengkapi profil Anda terlebih dahulu.');
            }
        }
        return $next($request);
    }
}
