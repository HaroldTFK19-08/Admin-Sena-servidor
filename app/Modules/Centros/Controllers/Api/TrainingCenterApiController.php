<?php

namespace App\Modules\Centros\Controllers\Api;

use App\Modules\Centros\Models\Trainingcenter;
use App\Modules\Centros\Requests\TrainingcentersRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class TrainingCenterApiController extends ApiController
{
    public function index()
    {
        $centros = Trainingcenter::latest()->get();
        return response()->json($centros);
    }

    public function store(TrainingcentersRequest $request)
    {
        $centro = Trainingcenter::create($request->validated());
        return response()->json($centro);
    }

    public function show(int $id)
    {
        $centro = Trainingcenter::with([
            'environments' => function ($query) {
                $query->select('id', 'nombre', 'trainingcenter_id');
            }
        ])->findOrFail($id);
        return response()->json($centro);
    }

    public function update(TrainingcentersRequest $request, Trainingcenter $centro)
    {
        $centro->update($request->validated());
        return response()->json($centro);
    }

    public function destroy(Trainingcenter $centro)
    {
        $centro->delete();
        return response()->json($centro);
    }
}
