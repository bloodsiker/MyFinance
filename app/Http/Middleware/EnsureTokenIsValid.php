<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTokenIsValid
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->bearerToken() != 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpYXQiOjE2MzA0NzcyOTAsIm5iZiI6MTYzMDQ3NzI5MCwianRpIjoiY2ZjYzFlMWEtYTE1NC00NDRiLWFmNGEtZTgwYzE2NTY3MGMxIiwiZXhwIjoxNjMyODk2NDkwLCJpZGVudGl0eSI6ImFuZHJleS5vZml0c2Vyb3ZAdy5tZXRyb2VuZ2luZXMuanAiLCJmcmVzaCI6ZmFsc2UsInR5cGUiOiJhY2Nlc3MifQ.uqqn1JBDV2wpvWf4RB-iPRWJiYjBQ02Y83vOSsDsqvE') {
            return response()->json([
                'result' => 'error',
                'error_description' => 'Not authorized'
            ], 401);
        }
        return $next($request);
    }
}
