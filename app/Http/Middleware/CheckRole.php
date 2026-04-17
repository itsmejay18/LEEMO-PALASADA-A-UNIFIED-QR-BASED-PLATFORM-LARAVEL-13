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
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $allowedRoles = collect($roles)
            ->flatMap(fn (string $role) => preg_split('/[|,]/', $role) ?: [])
            ->filter()
            ->values()
            ->all();

        if (! $request->user()->hasAnyRole($allowedRoles)) {
            abort(403, 'You are not authorized to access this area.');
        }

        return $next($request);
    }
}
