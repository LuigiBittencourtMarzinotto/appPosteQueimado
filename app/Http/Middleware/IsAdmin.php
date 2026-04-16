<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check() || auth()->user()->tipo !== 'ADMIN') {
            abort(403, 'Acesso restrito a administradores.');
        }

        return $next($request);
    }
}
