<?php

namespace App\Modules\Resultados\Requests;

use App\Modules\Resultados\Models\Grade;
use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class GradeApiRequest extends FormRequest
{
    use ResolvesApiUser;

    public function authorize(): bool
    {
        $user = $this->resolveApiUser($this);

        if (! $user && $this->apiPracticeMode()) {
            return true;
        }

        return in_array($user?->rol, ['Admin', 'Instructor'], true);
    }

    public function rules(): array
    {
        return [
            'result_id' => ['required', 'integer', 'exists:results,id'],
            'apprentice_id' => ['required', 'integer', 'exists:apprentices,id'],
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'estado_resultado' => ['required', Rule::in(Grade::ESTADOS)],
            'comentarios' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
