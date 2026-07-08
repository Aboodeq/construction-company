<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || ! $user->is_active || ! $user->hasAnyRole(['admin', 'editor'])) {
            abort(403, 'ليس لديك صلاحية للوصول إلى لوحة التحكم.');
        }

        return $next($request);
    }
}
