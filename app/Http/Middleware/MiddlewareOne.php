<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class MiddlewareOne
{
    public function handle(Request $request, Closure $next): Response
    {
        $role = $request->user()?->role ?? $request->session()->get('logged_role');

        if ($role !== 'admin') {
            return redirect()->route('home');
        }

        return $next($request);
    }
}
