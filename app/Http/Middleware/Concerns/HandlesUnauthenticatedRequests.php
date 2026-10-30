<?php

namespace App\Http\Middleware\Concerns;

use Illuminate\Http\Request;

trait HandlesUnauthenticatedRequests
{
    protected function unauthenticatedResponse(Request $request)
    {
        if ($request->expectsJson() || $request->is('v1/*') || $request->is('api/*')) {
            return response()->json([
                'message' => 'Unauthenticated.',
            ], 401, [
                'WWW-Authenticate' => 'Bearer',
            ]);
        }

        return redirect()->guest(route('auth.login'));
    }
}
