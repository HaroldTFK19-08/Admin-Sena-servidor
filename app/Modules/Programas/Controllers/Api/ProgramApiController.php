<?php

namespace App\Modules\Programas\Controllers\Api;

use App\Modules\Programas\Models\Program;
use App\Modules\Programas\Requests\ProgramRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class ProgramApiController extends ApiController
{
    public function index()
    {
        $programas = Program::with([
            'area' => function ($consulta) {
                $consulta->select('id', 'nombre');
            }
        ])->latest()->get();
        return response()->json($programas);
    }
    public function store(ProgramRequest $request)
    {
        $programa = Program::create($request->validated());
        return response()->json($programa);
    }

    public function show(int $id)
    {
        $programa = Program::with([
            'area' => function ($query) {
                $query->select('id', 'nombre', 'trainingcenter_id');
            }
        ])->findOrFail($id);
        return response()->json($programa);
    }

    public function update(ProgramRequest $request, Program $programa)
    {
        $programa->update($request->validated());
        return response()->json($programa);
    }

    public function destroy(Program $programa)
    {
        $programa->delete();
        return response()->json($programa);
    }
}
