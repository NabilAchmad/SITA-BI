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
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::user();
        if (!$user) return redirect()->route('login');

        $userRoles = $user->roles->pluck('nama_role')->toArray();

        if (empty(array_intersect($roles, $userRoles))) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}
