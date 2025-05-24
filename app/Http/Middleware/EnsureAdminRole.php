<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdminRole
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()?->role !== 'administrador') {
            return redirect('/dashboard');
        }

        return $next($request);
    }
}