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
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $usertype = Auth::user()->usertype ?? null;

        // Permissions for each user role
        $routePermissions = $this->getRoutePermissions();

        // Validate user type and redirect if unauthorized
        if (!$usertype || !array_key_exists($usertype, $routePermissions)) {
            return $this->denyAccess('Access Denied! User type not recognized.');
        }

        // Check access based on current route
        $currentRoute = $request->path();
        if (!$this->hasAccess($currentRoute, $routePermissions[$usertype])) {
            return $this->denyAccess('Access Denied! You do not have permission to access this resource.');
        }

        return $next($request);
    }

    /**
     * Get route permissions for user types.
     *
     * @return array
     */
    private function getRoutePermissions(): array
    {
        return [
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
    }

    /**
     * Check if a user has access to the current route.
     *
     * @param  string  $currentRoute
     * @param  array  $allowedRoutes
     * @return bool
     */
    private function hasAccess(string $currentRoute, array $allowedRoutes): bool
    {
        return collect($allowedRoutes)->contains(fn($route) => fnmatch($route, $currentRoute));
    }

    /**
     * Deny access and redirect with an error message.
     *
     * @param  string  $message
     * @return \Symfony\Component\HttpFoundation\Response
     */
    private function denyAccess(string $message): Response
    {
        Auth::logout();
        return redirect('/')->with('error', $message);
    }
}
