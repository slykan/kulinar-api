<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TokenFromQuery
{
    public function handle(Request $request, Closure $next)
    {
        // Ako nema Authorization headera, pokušaj čitati token iz query stringa
        if (!$request->bearerToken() && $request->query('token')) {
            $request->headers->set('Authorization', 'Bearer ' . $request->query('token'));
        }

        return $next($request);
    }
}
