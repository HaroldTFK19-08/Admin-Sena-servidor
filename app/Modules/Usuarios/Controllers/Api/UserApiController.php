<?php

namespace App\Modules\Usuarios\Controllers\Api;

use App\Modules\Usuarios\Models\User;
use App\Modules\Usuarios\Requests\UserApiRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\JsonResponse;
use Throwable;
use Illuminate\Support\Facades\Hash;

class UserApiController extends ApiController
{
    /**
     * Listar usuarios.
     */
    public function index(): JsonResponse
    {
        $usuarios = User::with([
            'admin' => function ($consulta) {
                $consulta->select('id', 'cargo', 'user_id');
            },
            'teacher' => function ($consulta) {
                $consulta->select(
                    'id',
                    'especialidad',
                    'tipo_instructor',
                    'area_id',
                    'user_id'
                );
            },
            'apprentice' => function ($consulta) {
                $consulta->select(
                    'id',
                    'estado',
                    'course_group_id',
                    'user_id'
                );
            },
            'aspirant' => function ($consulta) {
                $consulta->select('id', 'eps', 'user_id');
            }
        ])->latest()->get();

        return response()->json($usuarios);
    }

    /**
     * Crear usuario.
     */
    public function store(UserApiRequest $request): JsonResponse
    {
        $data = $request->validated();

        $archivo = null;

        try {

            if ($request->hasFile('foto_perfil')) {
                $archivo = $this->subirArchivo(
                    $request->file('foto_perfil'),
                    'usuario_' . uniqid(),
                    'adminsena/usuarios'
                );
                $data['foto_perfil'] = $archivo['url'];
                $data['public_id'] = $archivo['public_id'];
            }
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            $user = DB::transaction(function () use ($data) {
                $user = User::create($data);
                $this->guardarPerfil($user, $data);
                return $user;
            });
            return $this->created(
                $this->loadRelations($user),
                'Usuario creado correctamente.'
            );
        } catch (Throwable $e) {
            // Evitar dejar imágenes huérfanas si falla el registro.
            if ($archivo) {
                try {
                    $this->eliminarArchivo(
                        $archivo['public_id'],
                        $archivo['resource_type']
                    );
                } catch (Throwable $error) {
                    report($error);
                }
            }
            report($e);
            return $this->error('Error al crear el usuario.');
        }
    }
    /**
     * Mostrar usuario.
     */
    public function show(int $id): JsonResponse
    {
        $usuario = User::select([
            'id',
            'nombre_1',
            'nombre_2',
            'apellido_1',
            'apellido_2',
            'foto_perfil',
            'tipo_identificacion',
            'identificacion',
            'rol',
            'email',
        ])->findOrFail($id);
        if ($usuario->rol === 'Instructor') {
            $usuario->load([
                'teacher:id,user_id,area_id,especialidad,tipo_instructor,fecha_inicio,fecha_fin',
                'teacher.area:id,trainingcenter_id,nombre',
            ]);
        } elseif ($usuario->rol === 'Aprendiz') {
            $usuario->load([
                'apprentice:id,user_id,course_group_id,estado,fecha_inicio,fecha_fin',
                'apprentice.coursegroup:id,codigo,program_id',
                'apprentice.coursegroup.program:id,nombre',
            ]);
        } elseif ($usuario->rol === 'Admin') {
            $usuario->load([
                'admin:id,user_id,cargo,ubicacion_oficina,direccion',
            ]);
        } elseif ($usuario->rol === 'Aspirante') {
            $usuario->load('aspirant:id,user_id,eps');
        }
        return $this->success($usuario);
    }
    /**
     * Actualizar usuario.
     */
    public function update(
        UserApiRequest $request,
        User $usuario
    ): JsonResponse {
        $this->requireAdmin();
        $data = $request->validated();
        $archivoNuevo = null;
        $publicIdAnterior = $usuario->public_id;
        try {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            } else {
                unset($data['password']);
            }
            unset($data['password_confirmation']);
            if ($request->hasFile('foto_perfil')) {
                $archivoNuevo = $this->subirArchivo(
                    $request->file('foto_perfil'),
                    'usuario_' . uniqid(),
                    'adminsena/usuarios'
                );
                $data['foto_perfil'] = $archivoNuevo['url'];
                $data['public_id'] = $archivoNuevo['public_id'];
            } else {
                unset($data['foto_perfil']);
            }
            DB::transaction(function () use ($usuario, $data) {
                $usuario->update($data);
                $this->guardarPerfil($usuario, $data);
            });
            // Eliminar imagen anterior después de guardar los cambios.
            if ($archivoNuevo && $publicIdAnterior) {
                $this->eliminarArchivo($publicIdAnterior);
            }
            return $this->success(
                $this->loadRelations($usuario->fresh()),
                'Usuario actualizado correctamente.'
            );
        } catch (Throwable $e) {
            // Si falla la actualización, eliminar la imagen nueva.
            if ($archivoNuevo) {
                try {
                    $this->eliminarArchivo(
                        $archivoNuevo['public_id'],
                        $archivoNuevo['resource_type']
                    );
                } catch (Throwable $error) {
                    report($error);
                }
            }
            report($e);
            return $this->error('Error al actualizar el usuario.');
        }
    }
    /**
     * Eliminar usuario.
     */
    public function destroy(User $usuario): JsonResponse
    {
        $this->requireAdmin();
        abort_if(
            $usuario->id === $this->currentUser()?->id,
            422,
            'No puedes eliminar tu propio usuario.'
        );
        $publicId = $usuario->public_id;
        $usuario->delete();
        if ($publicId) {
            try {
                $this->eliminarArchivo($publicId);
            } catch (Throwable $e) {
                report($e);
            }
        }
        return $this->success(
            null,
            'Usuario eliminado correctamente.'
        );
    }
    /**
     * Crear o actualizar perfil según el rol.
     */
    private function guardarPerfil(User $usuario, array $data): void
    {
        if (
            $usuario->rol === 'Aprendiz'
            && !empty($data['course_group_id'])
        ) {
            $usuario->apprentice()->updateOrCreate(
                ['user_id' => $usuario->id],
                [
                    'course_group_id' => $data['course_group_id'],
                    'estado' => $data['estado']
                        ?? $usuario->apprentice?->estado
                        ?? 'Activo',
                    'fecha_inicio' => $data['fecha_inicio']
                        ?? $usuario->apprentice?->fecha_inicio
                        ?? now()->toDateString(),
                    'fecha_fin' => $data['fecha_fin'] ?? null,
                ]
            );
        }
        if (
            $usuario->rol === 'Instructor'
            && !empty($data['area_id'])
        ) {
            $usuario->teacher()->updateOrCreate(
                ['user_id' => $usuario->id],
                [
                    'area_id' => $data['area_id'],
                    'especialidad' => $data['especialidad'],
                    'tipo_instructor' => $data['tipo_instructor'],
                    'fecha_inicio' => $data['fecha_inicio']
                        ?? $usuario->teacher?->fecha_inicio
                        ?? now()->toDateString(),
                    'fecha_fin' => $data['fecha_fin'] ?? null,
                ]
            );
        }
    }
    /**
     * Cargar relaciones del usuario.
     */
    private function loadRelations(User $user): User
    {
        return $user->load([
            'admin',
            'teacher.area',
            'apprentice.coursegroup',
            'aspirant',
        ]);
    }
    /**
     * Respuesta de error.
     */
    private function error(string $message): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], 500);
    }
}
