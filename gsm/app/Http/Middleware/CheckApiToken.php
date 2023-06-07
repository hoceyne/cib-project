<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Symfony\Component\HttpFoundation\Response;

class CheckApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        
        
        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => 'Bearer '.$request->bearerToken(),
        ])->get(config('services.auth_server.url').'/api/user');

        if ($response->status() === 200) {
            $request->attributes->add(['auth_user' => $response->json()]);
            return $next($request);
        }

        abort(401, 'Unauthenticated');
   
    }
}
