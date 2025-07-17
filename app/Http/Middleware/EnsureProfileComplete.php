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
        $roleName = Auth::user()->getRoleNames()->first();
        if (Auth::check() && $roleName == 'Participant' || $roleName == 'Tenant') {
            $user = Auth::user();

            // Cek apakah ada data penting yang masih null
            if (
                is_null($user->phone_number) ||
                is_null($user->province) ||
                is_null($user->city) ||
                is_null($user->subdistrict) ||
                is_null($user->village) ||
                is_null($user->address)
            ) {
                notyf()->ripple(true)
                    ->warning('Silahkan lengkapi data profil Anda!');
                return redirect()->route('profileUserHomepage');
            }
        }
        return $next($request);
    }
}
