<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles A string of role names separated by a '|' pipe.
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $roles)
    {
        $user = Auth::user();

        // Jika tidak ada user yang login, redirect ke halaman login
        if (!$user) {
            return redirect()->route('login');
        }

        // 1. Pisahkan string roles menjadi sebuah array
        // Contoh: 'kaprodi-d3|kaprodi-d4' akan menjadi ['kaprodi-d3', 'kaprodi-d4']
        $rolesArray = explode('|', $roles);

        // 2. Gunakan method hasAnyRole() dari model User untuk memeriksa

        /** * @var \App\Models\User $user 
         * PERBAIKAN: Baris komentar di atas ditambahkan untuk memberitahu VS Code 
         * bahwa $user adalah instance dari App\Models\User, sehingga method 
         * hasAnyRole() akan dikenali dan tidak lagi berwarna merah.
         */
        if (!$user->hasAnyRole($rolesArray)) {
            // Jika tidak punya salah satu dari peran tersebut, tolak akses
            abort(403, 'AKSES DITOLAK. ANDA TIDAK MEMILIKI WEWENANG.');
        }

        // Jika user memiliki peran yang sesuai, lanjutkan request
        return $next($request);
    }
}
