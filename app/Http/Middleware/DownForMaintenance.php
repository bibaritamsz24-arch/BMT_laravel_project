<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DownForMaintenance
{
    /**
     * Redirect protected pages to the maintenance notice.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->routeIs('maintenance')) {
            return redirect()->route('maintenance');
        }

        return $next($request);
    }
}
