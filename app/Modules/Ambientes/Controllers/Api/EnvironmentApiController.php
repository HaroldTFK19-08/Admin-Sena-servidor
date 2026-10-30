<?php

namespace App\Modules\Ambientes\Controllers\Api;

use App\Modules\Ambientes\Models\Environment;
use App\Modules\Ambientes\Requests\EnvironmentRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class EnvironmentApiController extends ApiController
{
    public function index(Request $request)
    {
        $ambientes = Environment::with([
            'trainingcenter' => function ($consulta) {
                $consulta->select('id', 'nombre');
            }
        ])->latest()->get();
        return response()->json($ambientes);
    }

    public function store(EnvironmentRequest $request)
    {
        $ambiente = Environment::create($request->validated());
        return response()->json($ambiente);
    }
    public function show(int $id)
    {
        $ambiente = Environment::with([
            'trainingcenter' => function ($consulta) {
                $consulta->select('id', 'nombre');
            },
            'equipments' => function ($consulta) {
                $consulta->select('id', 'name', 'environment_id');
            }
        ])->findOrFail($id);

        return response()->json($ambiente);
    }
    public function update(EnvironmentRequest $request, Environment $ambiente)
    {
        $ambiente->update($request->validated());
        return response()->json($ambiente);
    }

    public function destroy(Environment $ambiente)
    {
        $ambiente->delete();
        return response()->json($ambiente);
    }
}
