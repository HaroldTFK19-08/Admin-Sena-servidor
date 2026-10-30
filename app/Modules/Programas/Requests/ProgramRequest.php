<?php

namespace App\Modules\Programas\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProgramRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'area_id' => ['required', 'integer', 'exists:areas,id'],
            'nombre' => ['required', 'string', 'min:3', 'max:255'],
            'codigo_programa' => ['required', 'string', 'max:50', Rule::unique('programs', 'codigo_programa')->ignore($this->route('programa'))],
            'nivel_programa' => ['required', Rule::in(['Tecnico', 'Tecnologo'])],
        ];
    }

    public function messages(): array
    {
        return [
            'area_id.required' => 'El área es obligatoria.',
            'area_id.integer' => 'El área no es válida.',
            'area_id.exists' => 'El área seleccionada no existe.',
            'nombre.required' => 'El nombre del programa es obligatorio.',
            'nombre.min' => 'El nombre del programa debe tener mínimo 3 caracteres.',
            'nombre.max' => 'El nombre del programa no puede superar los 255 caracteres.',
            'codigo_programa.required' => 'El código del programa es obligatorio.',
            'codigo_programa.max' => 'El código no puede superar los 50 caracteres.',
            'codigo_programa.unique' => 'El código del programa ya está registrado.',
            'nivel_programa.required' => 'El nivel del programa es obligatorio.',
            'nivel_programa.in' => 'El nivel seleccionado no es válido.',
        ];
    }
}
