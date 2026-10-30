<?php

namespace App\Modules\Fichas\Requests;

use App\Modules\Usuarios\Models\Teacher;
use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CourseGroupRequest extends FormRequest
{
    use ResolvesApiUser;

    public function authorize(): bool
    {
        if ($this->apiPracticeMode() && ! auth()->check() && ! $this->resolveApiUser($this)) {
            return true;
        }

        return auth()->check() || $this->resolveApiUser($this) !== null;
    }

    public function rules(): array
    {
        return [
            'program_id' => ['required', 'integer', 'exists:programs,id'],
            'teacher_id' => [
                'required',
                'integer',
                function ($attribute, $value, $fail) {
                    $teacher = Teacher::find($value);
                    if (! $teacher || strtolower($teacher->tipo_instructor) !== 'planta') {
                        $fail('El instructor seleccionado debe ser de tipo Planta para ser asignado a una ficha.');
                    }
                },
            ],
            'codigo' => ['required', 'string', 'max:255', Rule::unique('course_groups', 'codigo')->ignore($this->route('ficha'))],
            'capacidad_aprendices' => ['required', 'integer', 'min:1'],
        ];
    }
}
