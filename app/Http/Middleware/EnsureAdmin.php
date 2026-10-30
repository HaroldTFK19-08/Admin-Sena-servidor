<?php

namespace App\Http\Middleware;

use App\Http\Middleware\Concerns\HandlesUnauthenticatedRequests;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureAdmin
{
    use HandlesUnauthenticatedRequests;

    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()) {
            return $this->unauthenticatedResponse($request);
        }

        abort_unless($request->user()->rol === 'Admin', 403);

        return $next($request);
    }
}
