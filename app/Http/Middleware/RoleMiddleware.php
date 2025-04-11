<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, $role)
    {
        if (Session::get('role') !== $role) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}
