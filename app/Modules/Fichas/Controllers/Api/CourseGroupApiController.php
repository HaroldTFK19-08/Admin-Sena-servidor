<?php

namespace App\Modules\Fichas\Controllers\Api;

use App\Modules\Fichas\Models\Course_group;
use App\Modules\Fichas\Requests\CourseGroupRequest;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseGroupApiController extends ApiController
{
    public function index()
    {
        $fichas = Course_group::with(['program' => function ($consulta1) {
            $consulta1->select('id', 'nombre');
        }])->latest()->get();
        return response()->json($fichas);
    }

    public function store(CourseGroupRequest $request)
    {
        $data = $request->validated();
        $ficha = Course_group::create($data);
        $ficha->teachers()->sync($this->teacherIds($request, $data));
        return response()->json($this->loadRelations($ficha));
    }
    public function show(int $id)
    {
        $ficha = Course_group::with(['program' => function ($consulta1) {
            $consulta1->select('id', 'nombre');
        }])->findOrFail($id);
        return response()->json($this->loadRelations($ficha));
    }

    public function update(CourseGroupRequest $request, Course_group $ficha)
    {
        $data = $request->validated();
        $ficha->update($data);
        $ficha->teachers()->sync($this->teacherIds($request, $data));
        return response()->json($this->loadRelations($ficha));
    }

    public function destroy(Course_group $ficha)
    {
        $ficha->delete();
        return response()->json($ficha);
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

    private function loadRelations(Course_group $ficha): Course_group
    {
        return $ficha->load(['program.area.trainingcenter', 'teacher.user', 'teachers.user', 'apprentices.user'])->loadCount('apprentices');
    }
}
