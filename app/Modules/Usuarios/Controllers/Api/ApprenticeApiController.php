<?php

namespace App\Modules\Usuarios\Controllers\Api;

use App\Modules\Usuarios\Models\Apprentice;
use App\Modules\Usuarios\Models\User;
use App\Modules\Usuarios\Requests\PerfilAprendizRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ApprenticeApiController extends ApiController
{
    public function index()
    {
        $aprendices = Apprentice::with([
            'user' => function ($consulta1) {
                $consulta1->select('id', 'nombre_1', 'nombre_2', 'apellido_1', 'apellido_2', 'foto_perfil', 'tipo_identificacion', 'identificacion', 'email', 'rol');
            },
            'coursegroup' => function ($consulta2) {
                $consulta2->select('id', 'codigo', 'program_id');
            },
            'coursegroup.program' => function ($consulta3) {
                $consulta3->select('id', 'nombre');
            }
        ])->latest()->get();
        return response()->json($aprendices);
    }

    public function store(PerfilAprendizRequest $request)
    {
        $data = $request->validated();
        $data['rol'] = 'Aprendiz';
        $data['password'] = Hash::make($data['password']);
        $data['foto_perfil'] = $request->file('foto_perfil')?->store('photos', 'public');
        // Primero se crea el usuario y luego su perfil de aprendiz
        // (en una transacción: si falla el perfil, no queda el usuario suelto)
        $aprendiz = DB::transaction(function () use ($data) {
            $usuario = User::create($data);
            return $usuario->apprentice()->create($data);
        });
        return response()->json($aprendiz->load('user'), 201);
    }

    public function show(int $id)
    {
        $aprendiz = Apprentice::with([
            'user' => function ($consulta1) {
                $consulta1->select('id', 'nombre_1', 'nombre_2', 'apellido_1', 'apellido_2', 'foto_perfil', 'tipo_identificacion', 'identificacion', 'email', 'rol');
            },
            'coursegroup' => function ($consulta2) {
                $consulta2->select('id', 'codigo', 'program_id');
            },
            'coursegroup.program' => function ($consulta3) {
                $consulta3->select('id', 'nombre');
            }
        ])->findOrFail($id);
        return response()->json($aprendiz);
    }

    public function update(PerfilAprendizRequest $request, Apprentice $aprendiz)
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
        $aprendiz->user->update($data);
        $aprendiz->update($data);
        return response()->json($aprendiz->load('user'));
    }

    public function destroy(Apprentice $aprendiz)
    {
        // Al borrar el usuario, la base de datos borra también su perfil (cascade)
        $aprendiz->user->delete();
        return response()->json($aprendiz);
    }
}
