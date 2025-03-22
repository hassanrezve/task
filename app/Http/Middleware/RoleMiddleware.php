<?php

namespace App\Http\Middleware;

use App\Enums\Role;
use Closure;
use Illuminate\Http\Request;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $allowedRoles = array_map(function ($role) {
            return Role::from($role)->value;
        }, $roles);

        if (!in_array($request->user()->role->value, $allowedRoles)) {
            abort(403, 'Unauthorized');
        }

        return $next($request);
    }
}