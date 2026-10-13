<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HakAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(
        Request $request,
        Closure $next,
        ...$roles
    ): Response {

        // Pastikan pengguna sudah login
        if (!$request->user()) {
            return redirect()->route('HalamanLogin');
        }

        // Mendukung format role:Admin|Owner
        $allowedRoles = [];

        foreach ($roles as $role) {
            $allowedRoles = array_merge(
                $allowedRoles,
                explode('|', $role)
            );
        }

        // Periksa role pengguna
        if (in_array($request->user()->role, $allowedRoles)) {
            return $next($request);
        }

        // Jika tidak memiliki akses
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}
