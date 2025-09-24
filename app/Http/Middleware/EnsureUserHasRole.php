<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()->route('filament.admin.auth.login');
        }

        $user = Auth::user();

        if ($user->hasAnyRole(['admin', 'petugas', 'rektor', 'htl', 'penanggung jawab'])) {
            return $next($request);
        }


        Auth::logout();
        return redirect('/login')->with('error', 'Anda tidak memiliki hak akses ke panel admin.');
    }
}
