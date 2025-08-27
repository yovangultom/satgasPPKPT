<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Tambahkan ini
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAdminOrPetugas
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
            // Jika user adalah admin atau petugas, redirect mereka ke panel admin
            if ($user->hasAnyRole(['admin', 'petugas'])) {
                return redirect()->route('filament.admin.pages.dashboard'); // Redirect ke dashboard Filament
            }
        }

        return $next($request);
    }
}
