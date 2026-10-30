<?php

namespace App\Modules\Competencias\Controllers\Api;

use App\Modules\Competencias\Models\Competition;
use App\Modules\Competencias\Requests\CompetitionRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompetitionApiController extends ApiController
{
    public function index(Request $request)
    {
        $competencias = Competition::with(['teacher.user', 'teachers.user'])->latest()->get();
        return response()->json($competencias);
    }

    public function store(CompetitionRequest $request)
    {
        $data = $request->validated();
        $competencia = Competition::create($data);
        $competencia->teachers()->sync($this->teacherIds($request, $data));

        return response()->json($this->loadRelations($competencia));
    }

    public function show(int $id)
    {
        $competencia = Competition::with(['teacher.user', 'teachers.user'])->findOrFail($id);
        return response()->json($this->loadRelations($competencia));
    }

    public function update(CompetitionRequest $request, Competition $competencia)
    {
        $data = $request->validated();
        $competencia->update($data);
        $competencia->teachers()->sync($this->teacherIds($request, $data));

        return response()->json($this->loadRelations($competencia));
    }

    public function destroy(Competition $competencia)
    {
        $competencia->delete();

        return response()->json($competencia);
    }

    private function teacherIds(Request $request, array $data): array
    {
        $ids = $request->input('teacher_ids', !empty($data['teacher_id']) ? [$data['teacher_id']] : []);
        $ids = array_values(array_unique(array_filter((array) $ids)));
        Validator::make(['teacher_ids' => $ids], [
            'teacher_ids' => ['array'],
            'teacher_ids.*' => ['integer', 'exists:teachers,id'],
        ])->validate();
        return $ids;
    }

    private function loadRelations(Competition $competencia): Competition
    {
        return $competencia->load(['teacher.user', 'teachers.user', 'results'])->loadCount('results');
    }
}
