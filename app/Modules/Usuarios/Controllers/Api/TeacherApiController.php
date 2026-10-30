<?php

namespace App\Modules\Usuarios\Controllers\Api;

use App\Modules\Usuarios\Models\Teacher;
use App\Modules\Usuarios\Models\User;
use App\Modules\Usuarios\Requests\PerfilInstructorRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TeacherApiController extends ApiController
{
    public function index()
    {
        $instructores = Teacher::with([
            'user' => function ($consulta1) {
                $consulta1->select('id', 'nombre_1', 'nombre_2', 'apellido_1', 'apellido_2', 'foto_perfil', 'tipo_identificacion', 'identificacion', 'email', 'rol');
            },
            'area' => function ($consulta2) {
                $consulta2->select('id', 'nombre');
            }
        ])->latest()->get();
        return response()->json($instructores);
    }

    public function store(PerfilInstructorRequest $request)
    {
        $data = $request->validated();
        $data['rol'] = 'Instructor';
        $data['password'] = Hash::make($data['password']);
        $data['foto_perfil'] = $request->file('foto_perfil')?->store('photos', 'public');
        // Primero se crea el usuario y luego su perfil de instructor
        // (en una transacción: si falla el perfil, no queda el usuario suelto)
        $instructor = DB::transaction(function () use ($data) {
            $usuario = User::create($data);
            return $usuario->teacher()->create($data);
        });
        return response()->json($instructor->load('user'), 201);
    }

    public function show(int $id)
    {
        $instructor = Teacher::with([
            'user' => function ($consulta1) {
                $consulta1->select('id', 'nombre_1', 'nombre_2', 'apellido_1', 'apellido_2', 'foto_perfil', 'tipo_identificacion', 'identificacion', 'email', 'rol');
            },
            'area' => function ($consulta2) {
                $consulta2->select('id', 'nombre');
            },
            'coursegroups' => function ($consulta3) {
                $consulta3->select('course_groups.id', 'course_groups.codigo');
            }
        ])->findOrFail($id);
        return response()->json($instructor);
    }

    public function update(PerfilInstructorRequest $request, Teacher $instructor)
    {
        $data = $request->validated();
        if (! empty($data['password'])) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }
        if ($request->hasFile('foto_perfil')) {
            $data['foto_perfil'] = $request->file('foto_perfil')->store('photos', 'public');
        } else {
            unset($data['foto_perfil']);
        }
        $instructor->user->update($data);
        $instructor->update($data);
        return response()->json($instructor->load('user'));
    }

    public function destroy(Teacher $instructor)
    {
        $instructor->user->delete();
        return response()->json($instructor);
    }
}
