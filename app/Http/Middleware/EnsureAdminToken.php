<?php

namespace App\Http\Middleware;

use App\Support\Concerns\ResolvesApiUser;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Protege las rutas que solo puede usar el administrador.
 *
 * Lee el token Bearer que devuelve /v1/auth/login y deja pasar la petición
 * solo si el usuario existe y su rol es "Admin". Responde siempre en JSON.
 */
class EnsureAdminToken
{
    use ResolvesApiUser;

    public function handle(Request $request, Closure $next): Response
    {
        $user = $this->resolveApiUser($request);

        if (! $user) {
            return response()->json([
                'message' => 'Debes iniciar sesión como administrador.',
            ], 401, ['WWW-Authenticate' => 'Bearer']);
        }

        if ($user->rol !== 'Admin') {
            return response()->json([
                'message' => 'Solo el administrador puede realizar esta acción.',
            ], 403);
        }

        return $next($request);
    }
}
