<?php

namespace App\Modules\Resultados\Requests;

use App\Modules\Resultados\Models\Grade;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->rol === 'Instructor';
    }

    public function rules(): array
    {
        return [
            'calificaciones' => ['required', 'array'],
            'calificaciones.*.result_id' => ['required', 'integer', 'exists:results,id'],
            'calificaciones.*.apprentice_id' => ['required', 'integer', 'exists:apprentices,id'],
            'calificaciones.*.estado_resultado' => ['required', Rule::in(Grade::ESTADOS)],
            'calificaciones.*.comentarios' => ['nullable', 'string'],
        ];
    }
}
