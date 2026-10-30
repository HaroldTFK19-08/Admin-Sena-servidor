<?php

namespace App\Modules\Resultados\Controllers\Api;

use App\Modules\Resultados\Models\Grade;
use App\Modules\Resultados\Models\Result;
use App\Modules\Resultados\Requests\GradeApiRequest;
use App\Modules\Usuarios\Models\Apprentice;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class GradeApiController extends ApiController
{
    public function index(Request $request)
    {
        $calificaciones = Grade::with(['result.competition', 'apprentice.user', 'teacher.user'])->latest()->get();
        return response()->json($calificaciones);
    }

    public function store(GradeApiRequest $request)
    {
        $data = $request->validated();
        $this->authorizeAcademicData($data);

        $grade = Grade::updateOrCreate(
            [
                'result_id' => $data['result_id'],
                'apprentice_id' => $data['apprentice_id'],
            ],
            [
                'teacher_id' => $this->teacherId($data['teacher_id'] ?? null),
                'estado_resultado' => $data['estado_resultado'],
                'comentarios' => $data['comentarios'] ?? null,
            ]
        );

        return response()->json($this->loadRelations($grade));
    }

    public function show(int $id)
    {
        $calificacion = Grade::with(['result.competition', 'apprentice.user', 'teacher.user'])->findOrFail($id);
        return response()->json($this->loadRelations($calificacion));
    }

    public function update(GradeApiRequest $request, Grade $calificacion)
    {
        $this->authorizeGrade($calificacion);
        $data = $request->validated();
        $this->authorizeAcademicData($data);

        $calificacion->update([
            'result_id' => $data['result_id'],
            'apprentice_id' => $data['apprentice_id'],
            'teacher_id' => $this->teacherId($data['teacher_id'] ?? null),
            'estado_resultado' => $data['estado_resultado'],
            'comentarios' => $data['comentarios'] ?? null,
        ]);

        return response()->json($this->loadRelations($calificacion));
    }

    public function destroy(Grade $calificacion)
    {
        $this->authorizeGrade($calificacion);
        $calificacion->delete();

        return response()->json($calificacion);
    }

    private function authorizeAcademicData(array $data): void
    {
        $user = $this->currentUser();

        if (! $user || $user->rol === 'Admin') {
            return;
        }

        $teacher = $user->teacher;
        $apprentice = Apprentice::with('coursegroup')->find($data['apprentice_id']);
        $result = Result::with('competition')->find($data['result_id']);

        abort_unless(
            $teacher
            && $apprentice?->coursegroup?->estaAsignadaAlInstructor($teacher)
            && $result?->competition?->estaAsignadaAlInstructor($teacher),
            403,
            'El aprendiz o el resultado no pertenece a tu ámbito académico.'
        );
    }

    private function authorizeGrade(Grade $calificacion): void
    {
        $user = $this->currentUser();

        if (! $user || $user->rol === 'Admin') {
            return;
        }

        $teacher = $user->teacher;
        abort_unless(
            $teacher && $calificacion->apprentice?->coursegroup?->estaAsignadaAlInstructor($teacher),
            403,
            'No tienes acceso a esta calificación.'
        );
    }

    private function teacherId(?int $requestedTeacherId = null): ?int
    {
        $user = $this->currentUser();

        if ($user?->rol === 'Instructor') {
            return $user->teacher?->id;
        }

        return $requestedTeacherId;
    }

    private function loadRelations(Grade $grade): Grade
    {
        return $grade->load(['result.competition', 'apprentice.user', 'apprentice.coursegroup', 'teacher.user']);
    }
}
