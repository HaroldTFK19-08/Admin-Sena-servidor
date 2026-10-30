<?php

namespace App\Modules\Asignaciones\Controllers\Api;

use App\Modules\Asignaciones\Models\Assignment;
use App\Modules\Asignaciones\Requests\AssignmentRequest;
use App\Modules\Usuarios\Models\Apprentice;
use App\Http\Controllers\Api\ApiController;
use Illuminate\Http\Request;

class AssignmentApiController extends ApiController
{
    public function index(Request $request)
    {
        $asignaciones = Assignment::with(['equipment.environment', 'apprentice.user', 'apprentice.coursegroup'])->latest()->get();
        return response()->json($asignaciones);
    }

    public function store(AssignmentRequest $request)
    {
        $user = $this->requireRole(['Admin', 'Instructor']);
        $data = $request->validated();
        $this->authorizeApprentice($user, Apprentice::findOrFail($data['apprentice_id']));

        $asignacion = Assignment::create($data);

        return response()->json($this->loadRelations($asignacion));
    }

    public function show(int $id)
    {
        $asignacion = Assignment::with(['equipment.environment', 'apprentice.user', 'apprentice.coursegroup'])->findOrFail($id);
        $this->authorizeAssignment($asignacion);

        return response()->json($this->loadRelations($asignacion));
    }

    public function update(AssignmentRequest $request, Assignment $asignacion)
    {
        $user = $this->requireRole(['Admin', 'Instructor']);
        $this->authorizeAssignment($asignacion);
        $this->authorizeApprentice($user, Apprentice::findOrFail($request->integer('apprentice_id')));
        $asignacion->update($request->validated());

        return response()->json($this->loadRelations($asignacion->fresh()));
    }

    public function destroy(Assignment $asignacion)
    {
        $this->authorizeAssignment($asignacion);
        $asignacion->delete();

        return response()->json($asignacion);
    }

    private function scopeQuery($query, $user): void
    {
        if (! $user) {
            return;
        }

        if ($user->rol === 'Aprendiz') {
            $query->whereHas('apprentice', function ($aprendices) use ($user) {
                $aprendices->where('user_id', $user->id);
            });

            return;
        }

        if ($user->rol === 'Instructor') {
            $teacher = $user->teacher;
            $query->whereHas('apprentice.coursegroup', function ($fichas) use ($teacher) {
                if ($teacher) {
                    $fichas->asignadasAlInstructor($teacher);
                } else {
                    $fichas->whereRaw('1 = 0');
                }
            });
        }
    }

    private function authorizeApprentice($user, Apprentice $apprentice): void
    {
        if (! $user || $user->rol === 'Admin') {
            return;
        }

        $teacher = $user->teacher;
        abort_unless(
            $teacher && $apprentice->coursegroup?->estaAsignadaAlInstructor($teacher),
            403,
            'El aprendiz no pertenece a tu ámbito de fichas.'
        );
    }

    private function authorizeAssignment(Assignment $asignacion): void
    {
        $user = $this->requireRole(['Admin', 'Instructor', 'Aprendiz']);
        $asignacion->loadMissing('apprentice.coursegroup');

        if (! $user) {
            return;
        }

        if ($user->rol === 'Aprendiz') {
            abort_unless($asignacion->apprentice?->user_id === $user->id, 403, 'No tienes acceso a esta asignación.');

            return;
        }

        if ($user->rol === 'Instructor') {
            $teacher = $user->teacher;
            abort_unless($teacher && $asignacion->apprentice?->coursegroup?->estaAsignadaAlInstructor($teacher), 403, 'No tienes acceso a esta asignación.');
        }
    }

    private function loadRelations(Assignment $asignacion): Assignment
    {
        return $asignacion->load(['equipment.environment', 'apprentice.user', 'apprentice.coursegroup.program']);
    }
}
