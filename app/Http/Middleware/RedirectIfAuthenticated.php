<?php

namespace App\Http\Middleware;

// FILE: app/Http/Middleware/RedirectIfAuthenticated.php
// Middleware bawaan Laravel — MODIFIKASI bagian redirect-nya

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     * Jika user sudah login dan mencoba buka /login atau /register,
     * langsung redirect ke dashboard sesuai role mereka.
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                // Redirect sesuai role
                if ($user->role === 'admin') {
                    return redirect()->route('admin.dashboard');
                }

                return redirect()->route('siswa.dashboard');
            }
        }

        return $next($request);
    }
}