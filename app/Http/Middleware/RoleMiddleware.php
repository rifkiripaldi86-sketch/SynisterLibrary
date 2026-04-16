<?php

namespace App\Http\Middleware;

// FILE: app/Http/Middleware/RoleMiddleware.php

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Middleware ini memastikan user hanya bisa mengakses
     * halaman sesuai role mereka (admin atau siswa).
     *
     * Cara daftar di Kernel.php:
     *   'role' => \App\Http\Middleware\RoleMiddleware::class,
     *
     * Cara pakai di Route:
     *   Route::middleware(['role:admin'])->group(...)
     *   Route::middleware(['role:siswa'])->group(...)
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // Pastikan user sudah login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Cek apakah role user cocok dengan yang dibutuhkan
        if (auth()->user()->role !== $role) {
            // Jika tidak cocok, redirect ke dashboard yang sesuai rolenya
            if (auth()->user()->role === 'admin') {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
            }

            return redirect()->route('siswa.dashboard')
                ->with('error', 'Anda tidak memiliki akses ke halaman tersebut.');
        }

        return $next($request);
    }
}