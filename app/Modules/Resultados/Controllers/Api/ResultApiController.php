<?php

namespace App\Modules\Resultados\Controllers\Api;

use App\Modules\Resultados\Models\Result;
use App\Modules\Resultados\Requests\ResultRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class ResultApiController extends ApiController
{
    public function index(Request $request)
    {
        $resultados = Result::with('competition')->latest()->get();
        return response()->json($resultados);
    }

    public function store(ResultRequest $request)
    {
        $data = $request->validated();
        $cantidad = max(1, (int) ($data['cantidad'] ?? 1));
        unset($data['cantidad']);

        $resultados = collect(range(1, $cantidad))->map(function (int $indice) use ($data, $cantidad) {
            return Result::create([
                ...$data,
                'codigo' => !empty($data['codigo']) ? ($cantidad === 1 ? $data['codigo'] : $data['codigo'].'-'.$indice) : null,
                'nombre' => $cantidad === 1 ? $data['nombre'] : trim($data['nombre'].' '.$indice),
            ]);
        });

        return response()->json($cantidad === 1 ? $resultados->first() : $resultados);
    }

    public function show(int $id)
    {
        $resultado = Result::with('competition')->findOrFail($id);
        return response()->json($this->loadRelations($resultado));
    }

    public function update(ResultRequest $request, Result $resultado)
    {
        $data = $request->validated();
        unset($data['cantidad']);
        $resultado->update($data);

        return response()->json($this->loadRelations($resultado));
    }

    public function destroy(Result $resultado)
    {
        if (! $resultado->puedeEliminarse()) {
            return response()->json([
                'success' => false,
                'message' => 'No se puede eliminar el resultado porque tiene calificaciones asociadas.',
            ], 409);
        }

        $resultado->delete();

        return response()->json($resultado);
    }

    private function loadRelations(Result $resultado): Result
    {
        return $resultado->load(['competition', 'grades.apprentice.user', 'grades.teacher.user'])->loadCount('grades');
    }
}
