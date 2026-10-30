<?php

namespace App\Modules\Auth\Controllers\Api;

use App\Modules\Auth\Requests\LoginRequest;
use App\Modules\Auth\Requests\RegisterRequest;
use App\Modules\Usuarios\Models\User;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Throwable;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;

class AuthApiController extends ApiController
{
    /**
     * Iniciar sesión.
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => 'Las credenciales no son correctas.',
            ]);
        }

        $token = $user->createToken(
            $request->input('device_name', 'api-token')
        )->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ]);
    }

    /**
     * Registrar usuario.
     */
    public function register(RegisterRequest $request)
    {
        $data = $request->validated();

        $roles = [
            'aprendiz' => 'Aprendiz',
            'instructor' => 'Instructor',
            'aspirante' => 'Aspirante',
        ];

        $data['rol'] = $roles[strtolower((string) $data['rol'])] ?? $data['rol'];

        $data['password'] = Hash::make($data['password']);

        $archivoCloudinary = null;

        // Subir fotografía a Cloudinary.
        if ($request->hasFile('foto_perfil')) {
            $archivoCloudinary = $this->subirArchivo(
                $request->file('foto_perfil'),
                'usuario_' . uniqid(),
                'adminsena/usuarios'
            );

            $data['foto_perfil'] = $archivoCloudinary['url'];
            $data['public_id'] = $archivoCloudinary['public_id'];
        } else {
            unset($data['foto_perfil']);
        }

        try {
            $user = DB::transaction(function () use ($data) {

                $user = User::create($data);

                // Crear perfil de aprendiz.
                if ($user->rol === 'Aprendiz') {
                    $user->apprentice()->create([
                        'course_group_id' => $data['course_group_id'],
                        'estado' => 'Activo',
                        'fecha_inicio' => $data['fecha_inicio'],
                        'fecha_fin' => $data['fecha_fin'] ?? null,
                    ]);
                }

                // Crear perfil de instructor.
                elseif ($user->rol === 'Instructor') {
                    $user->teacher()->create([
                        'area_id' => $data['area_id'],
                        'especialidad' => $data['especialidad'],
                        'tipo_instructor' => $data['tipo_instructor'],
                        'fecha_inicio' => $data['fecha_inicio'],
                        'fecha_fin' => $data['fecha_fin'] ?? null,
                    ]);
                }

                return $user;
            });
        } catch (Throwable $e) {

            // Si falla el registro, eliminar la imagen subida.
            if ($archivoCloudinary) {
                try {
                    $this->eliminarArchivo($archivoCloudinary['public_id']);
                } catch (Throwable $error) {
                    report($error);
                }
            }

            throw $e;
        }

        $user->load([
            'teacher.area',
            'apprentice.coursegroup',
        ]);

        $token = $user->createToken(
            $request->input('device_name', 'api-token')
        )->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }

    /**
     * Obtener usuario autenticado.
     */
    public function me(Request $request)
    {
        $user = $this->currentUser();

        if (!$user) {
            return response()->json(null);
        }

        $user->load([
            'admin',
            'teacher',
            'apprentice',
            'aspirant',
        ]);

        return response()->json($user);
    }

    /**
     * Actualizar perfil del usuario autenticado.
     */
    public function updateProfile(Request $request)
    {
        $user = $this->currentUser();

        abort_unless(
            $user,
            401,
            'Debes iniciar sesión para actualizar tu perfil.'
        );

        $data = $request->validate([
            'nombre_1' => ['required', 'string', 'max:20'],
            'nombre_2' => ['nullable', 'string', 'max:20'],
            'apellido_1' => ['required', 'string', 'max:20'],
            'apellido_2' => ['nullable', 'string', 'max:20'],

            'tipo_identificacion' => [
                'required',
                Rule::in(['CC', 'TI', 'CE', 'PPT']),
            ],

            'identificacion' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'identificacion')->ignore($user->id),
            ],

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($user->id),
            ],

            'password' => [
                'nullable',
                'string',
                'min:8',
                'confirmed',
            ],

            'password_confirmation' => [
                'nullable',
                'required_with:password',
                'string',
                'min:8',
            ],

            'foto_perfil' => [
                'nullable',
                'image',
                'mimes:png,jpg,jpeg,webp',
                'max:2048',
            ],
        ]);

        // Encriptar contraseña si se proporcionó una nueva.
        if (!empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        unset($data['password_confirmation']);

        $archivoAnterior = $user->public_id;
        $archivoNuevo = null;

        // Subir nueva fotografía a Cloudinary.
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

        try {
            $user->update($data);
        } catch (Throwable $e) {

            // Si falla la actualización, eliminar la nueva imagen.
            if ($archivoNuevo) {
                try {
                    $this->eliminarArchivo($archivoNuevo['public_id']);
                } catch (Throwable $error) {
                    report($error);
                }
            }

            throw $e;
        }

        // Eliminar fotografía anterior después de guardar la nueva.
        if ($archivoNuevo && $archivoAnterior) {
            try {
                $this->eliminarArchivo($archivoAnterior);
            } catch (Throwable $e) {
                report($e);
            }
        }

        return response()->json($user->fresh());
    }

    /**
     * Cerrar sesión.
     */
    public function logout(Request $request)
    {
        // Eliminar el token recibido en Authorization: Bearer.
        $token = $request->bearerToken();

        if ($token) {
            PersonalAccessToken::findToken($token)?->delete();
        }

        return response()->json([
            'message' => 'Sesión cerrada correctamente.',
        ]);
    }
}
