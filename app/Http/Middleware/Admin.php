<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class Admin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usertype = Auth::user()->usertype ?? null;

        // Define access permissions for each user type
        $routePermissions = [
            'admin' => [
                'admin/dashboard',
                'view_category',
                'add_category',
                'delete_category/*',
                'edit_category/*',
                'update_category/*',
                'add_product',
                'upload_product',
                'view_product',
                'delete_product/*',
                'update_product/*',
                'edit_product/*',
                'product_search',
                'view_orders',
                'on_the_way/*',
                'delivered/*',
                'print_pdf/*',
            ],
            'operationm' => [
                'admin/dashboard',
                'view_category',
                'add_category',
                'delete_category/*',
                'edit_category/*',
                'update_category/*',
                'add_product',
                'upload_product',
                'view_product',
                'delete_product/*',
                'update_product/*',
                'edit_product/*',
                'product_search',
            ],
            'salesm' => [
                'admin/dashboard',
                'view_orders',
                'on_the_way/*',
                'delivered/*',
                'print_pdf/*',
            ],
        ];

        // Redirect to home if the user type is invalid or doesn't exist in the permissions
        if (!$usertype || !isset($routePermissions[$usertype])) {
            Auth::logout();
            return redirect('/')->with('error', 'Access Denied! User type not recognized.');
        }

        // Check if the current route matches any allowed patterns for the user's role
        $currentRoute = $request->path();
        $allowedRoutes = $routePermissions[$usertype];
        $hasAccess = collect($allowedRoutes)->contains(function ($allowedRoute) use ($currentRoute) {
            return fnmatch($allowedRoute, $currentRoute);
        });

        // Deny access if no matching route is found
        if (!$hasAccess) {
            Auth::logout();
            return redirect('/')->with('error', 'Access Denied! You do not have permission to access this resource.');
        }

        return $next($request);
    }
}
