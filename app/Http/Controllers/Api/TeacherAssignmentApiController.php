<?php

namespace App\Http\Controllers\Api;

use App\Modules\Competencias\Models\CompetitionTeacher;
use App\Modules\Fichas\Models\CourseGroupTeacher;
use Illuminate\Http\Request;

class TeacherAssignmentApiController extends ApiController
{
    public function index(Request $request)
    {
        $this->requireAdmin();
        $config = $this->config($request);
        $query = $config['model']::query()->with($config['relations']);

        foreach ($config['fields'] as $field) {
            if ($request->filled($field)) {
                $query->where($field, $request->input($field));
            }
        }

        return response()->json($query->orderByDesc('id')->get());
    }

    public function store(Request $request)
    {
        $this->requireAdmin();
        $config = $this->config($request);
        $data = $request->validate($this->rules($config));
        $assignment = $config['model']::firstOrCreate($data);

        return response()->json($assignment->load($config['relations']));
    }

    public function show(Request $request, int $assignmentId)
    {
        $this->requireAdmin();
        $config = $this->config($request);

        return response()->json($config['model']::with($config['relations'])->findOrFail($assignmentId));
    }

    public function update(Request $request, int $assignmentId)
    {
        $this->requireAdmin();
        $config = $this->config($request);
        $assignment = $config['model']::findOrFail($assignmentId);
        $assignment->update($request->validate($this->rules($config)));

        return response()->json($assignment->fresh($config['relations']));
    }

    public function destroy(Request $request, int $assignmentId)
    {
        $this->requireAdmin();
        $config = $this->config($request);
        $assignment = $config['model']::findOrFail($assignmentId);
        $assignment->delete();

        return response()->json($assignment);
    }

    private function config(Request $request): array
    {
        return match ($request->route('assignmentType')) {
            'competencias-instructores' => [
                'model' => CompetitionTeacher::class,
                'label' => 'Asignaciones de competencias',
                'singular' => 'Instructor de competencia',
                'fields' => ['competency_id', 'teacher_id'],
                'relations' => ['competition', 'teacher.user'],
                'rules' => [
                    'competency_id' => ['required', 'integer', 'exists:competitions,id'],
                    'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
                ],
            ],
            'fichas-instructores' => [
                'model' => CourseGroupTeacher::class,
                'label' => 'Asignaciones de fichas',
                'singular' => 'Instructor de ficha',
                'fields' => ['course_group_id', 'teacher_id'],
                'relations' => ['courseGroup', 'teacher.user'],
                'rules' => [
                    'course_group_id' => ['required', 'integer', 'exists:course_groups,id'],
                    'teacher_id' => ['required', 'integer', 'exists:teachers,id'],
                ],
            ],
            default => abort(404),
        };
    }

    private function rules(array $config): array
    {
        return array_intersect_key($config['rules'], array_flip($config['fields']));
    }
}
