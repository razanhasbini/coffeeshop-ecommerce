<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->check()) {
            return redirect()->route('login');
        }

        abort_unless(auth()->user()->isSuperAdmin(), 403, 'Super administrator access required.');

        return $next($request);
    }
}
