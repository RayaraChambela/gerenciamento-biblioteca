<?php

namespace App\Http\Middleware;

use App\Enums\UserRole;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserHasRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        $userRole = $request->user()?->role;

        abort_unless(
            $userRole instanceof UserRole && $userRole->value === $role,
            403,
            'Você não tem permissão para acessar esta área.'
        );

        return $next($request);
    }
}
