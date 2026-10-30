<?php

namespace App\Modules\Equipos\Controllers\Api;

use App\Modules\Equipos\Models\Equipment;
use App\Modules\Equipos\Requests\EquipmentRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class EquipmentApiController extends ApiController
{
    public function index()
    {
        $equipos = Equipment::with([
            'environment' => function ($consulta1) {
                $consulta1->select('id', 'nombre');
            }
        ])->get();
        return response()->json($equipos);
    }
    public function store(EquipmentRequest $request)
    {
        $equipo = Equipment::create($request->validated());
        return response()->json($equipo);
    }
    public function show(int $id)
    {
        $equipo = Equipment::with([
            'environment' => function ($consulta) {
                $consulta->select('id', 'nombre');
            }
        ])->findOrFail($id);
        return response()->json($equipo);
    }

    public function update(EquipmentRequest $request, Equipment $equipo)
    {
        $equipo->update($request->validated());
        return response()->json($equipo);
    }

    public function destroy(Equipment $equipo)
    {
        $equipo->delete();
        return response()->json($equipo);
    }
}
