<?php

namespace App\Modules\Competencias\Requests;

use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompetitionRequest extends FormRequest
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
            'teacher_id' => ['nullable', 'integer', 'exists:teachers,id'],
            'codigo' => ['nullable', 'string', 'max:255', Rule::unique('competitions', 'codigo')->ignore($this->route('competencia'))],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['required', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }
}
