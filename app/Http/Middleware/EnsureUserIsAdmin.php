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

        // Any assigned role grants entry to the panel itself; what a user can
        // actually do once inside is governed entirely by that role's
        // permissions (checked per-action, e.g. $this->authorize('services.edit')).
        // This is deliberately not a hardcoded role list so that custom roles
        // created via the Roles admin screen work without a code change.
        if (! $user || ! $user->is_active || $user->roles->isEmpty()) {
            abort(403, 'ليس لديك صلاحية للوصول إلى لوحة التحكم.');
        }

        return $next($request);
    }
}
