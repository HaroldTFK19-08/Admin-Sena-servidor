<?php

namespace App\Modules\Areas\Controllers\Api;

use App\Modules\Areas\Models\Area;
use App\Modules\Areas\Requests\AreaRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class AreaApiController extends ApiController
{
    public function index()
    {
        $areas = Area::with([
            'trainingcenter' => function ($consulta) {
                $consulta->select('id', 'nombre');
            }
        ])->get();
        return response()->json($areas);
    }

    public function store(AreaRequest $request)
    {
        $area = Area::create($request->validated());
        return response()->json($area);
    }

    public function show(int $id)
    {
        $area = Area::with([
            'trainingcenter' => function ($consulta) {
                $consulta->select('id', 'nombre');
            },
            'programs' => function ($consulta) {
                $consulta->select('id', 'nombre', 'area_id');
            }
        ])
            ->findOrFail($id);
        return response()->json($area);
    }

    public function update(AreaRequest $request, Area $area)
    {
        $area->update($request->validated());
        return response()->json($area);
    }
    public function destroy(Area $area)
    {
        $area->delete();
        return response()->json($area);
    }
}
