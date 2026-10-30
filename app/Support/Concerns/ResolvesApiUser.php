<?php

namespace App\Support\Concerns;

use App\Modules\Usuarios\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\PersonalAccessToken;

/**
 * Resuelve el usuario de una petición API a partir del token Bearer.
 *
 * Las rutas API de AdminSena no registran middleware de autenticación (el
 * proyecto es de práctica), por lo que `Request::user()` no resuelve el token
 * automáticamente. Esta trait deja el usuario disponible tanto para las
 * validaciones de rol de los Form Requests como para los controladores, sin
 * volver a proteger las rutas.
 */
trait ResolvesApiUser
{
    protected function resolveApiUser(?Request $request = null): ?User
    {
        $request ??= request();

        $user = $request->user();

        if ($user instanceof User) {
            return $user;
        }

        $token = $request->bearerToken();

        if (! $token) {
            return null;
        }

        $user = PersonalAccessToken::findToken($token)?->tokenable;

        if ($user instanceof User) {
            $request->setUserResolver(fn () => $user);
            Auth::setUser($user);
        }

        return $user instanceof User ? $user : null;
    }

    /**
     * Indica si la API responde sin token (proyecto de práctica).
     */
    protected function apiPracticeMode(): bool
    {
        return (bool) config('adminsena.practice_mode', true);
    }

    /**
     * Usuario de la petición si su rol está entre los permitidos.
     *
     * En modo práctica devuelve `null` cuando no llega un token válido, para
     * que el controlador siga con la operación sin alcance de usuario. Con
     * `API_PRACTICE_MODE=false` vuelve a abortar con `403`.
     */
    protected function resolveApiRole(string|array $roles, ?Request $request = null): ?User
    {
        $roles = (array) $roles;
        $user = $this->resolveApiUser($request);

        if ($user && in_array($user->rol, $roles, true)) {
            return $user;
        }

        if ($this->apiPracticeMode()) {
            return null;
        }

        abort(403, 'No tienes permisos para realizar esta acción.');
    }
}
