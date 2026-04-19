<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        if (auth()->user()->role !== $role) {
            // Redirect based on actual role to their correct dashboard
            $url = match(auth()->user()->role) {
                'retailer' => '/retailer/dashboard',
                'wholesaler' => '/wholesaler/dashboard',
                'distributor' => '/distributor/dashboard',
                'nestle' => '/nestle/dashboard',
                default => '/'
            };
            
            return redirect($url)->with('error', "Access Denied. Your account does not have $role privileges.");
        }

        return $next($request);
    }
}
