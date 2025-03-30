<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if the authenticated user is a customer
        if (Auth::check() && Auth::user()->usertype === 'user') {
            return $next($request);
        }

        // Redirect unauthorized users
        return $this->denyAccess();
    }

    /**
     * Deny access and redirect to the home page with an error message.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function denyAccess(): Response
    {
        Auth::logout();
        return redirect('/')->with('error', 'Access Denied!');
    }
}
