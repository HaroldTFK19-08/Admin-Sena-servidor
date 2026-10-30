<?php

namespace App\Http\Controllers\Api;

use App\Modules\Usuarios\Models\Admin;
use App\Modules\Usuarios\Models\User;
use App\Http\Controllers\CloudinaryController;
use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

use Illuminate\Http\Request;

abstract class ApiController extends CloudinaryController
{
    use ResolvesApiUser;

    protected function success(
        mixed $data = null,
        string $message = 'Operación realizada correctamente.',
        int $status = 200
    ) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    protected function created(
        mixed $data = null,
        string $message = 'Registro creado correctamente.',
        int $status = 201
    ) {
        return $this->success($data, $message, $status);
    }

    protected function paginated(
        LengthAwarePaginator $paginator,
        string $message = 'Listado obtenido correctamente.'
    ) {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'from' => $paginator->firstItem(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'to' => $paginator->lastItem(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * Usuario de la petición si su rol está permitido.
     */
    protected function requireRole(string|array $roles): ?User
    {
        return $this->resolveApiRole($roles);
    }

    /**
     * El administrador siempre necesita autenticación.
     */
    protected function requireAdmin(): User
    {
        $user = $this->currentUser();

        abort_unless(
            $user,
            401,
            'Debes iniciar sesión como administrador.'
        );

        abort_unless(
            $user->rol === 'Admin',
            403,
            'Solo el administrador puede realizar esta acción.'
        );

        return $user;
    }

    /**
     * Perfil de administrador usado en las tablas que exigen admin_id.
     */
    protected function adminProfileId(?User $user = null): int
    {
        $adminId = $user?->admin?->id
            ?? Admin::query()->orderBy('id')->value('id');

        abort_if(
            !$adminId,
            422,
            'No hay un perfil de administrador disponible para completar la operación.'
        );

        return (int) $adminId;
    }

    /**
     * Usuario actual de la petición.
     */
    protected function currentUser(): ?User
    {
        return $this->resolveApiUser();
    }

    /**
     * Aplicar paginación.
     */
    protected function applyPagination(
        $query,
        Request $request,
        int $perPage = 15
    ) {
        return $query
            ->paginate(
                min(100, max(1, (int) $request->integer('per_page', $perPage)))
            )
            ->withQueryString();
    }
}
