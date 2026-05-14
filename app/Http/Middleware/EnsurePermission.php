<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class EnsurePermission
{
    /**
     * Usage in routes: ->middleware('permission:properties.view')
     * or any-of:       ->middleware('permission:properties.view|properties.edit')
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = Auth::user();

        if (! $user) {
            return redirect()->route('admin.login');
        }

        // Allow super_admin everywhere.
        if (method_exists($user, 'isSuperAdmin') && $user->isSuperAdmin()) {
            return $next($request);
        }

        $required = [];
        foreach ($permissions as $p) {
            $required = array_merge($required, explode('|', $p));
        }
        $required = array_filter($required);

        if (empty($required)) {
            return $next($request);
        }

        if (! $user->hasAnyPermission($required)) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => __('messages.auth.no_permission'),
                ], 403);
            }

            abort(403, __('messages.auth.no_permission'));
        }

        return $next($request);
    }
}
