<?php

namespace App\Modules\Areas\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AreaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'trainingcenter_id' => ['required', 'integer', 'exists:trainingcenters,id'],
            'nombre' => ['required', 'string', 'min:3', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'trainingcenter_id.required' => 'El centro de formación es obligatorio.',
            'trainingcenter_id.integer' => 'El centro de formación no es válido.',
            'trainingcenter_id.exists' => 'El centro seleccionado no existe.',
            'nombre.required' => 'El nombre del área es obligatorio.',
            'nombre.string' => 'El nombre del área debe ser texto.',
            'nombre.min' => 'El nombre del área debe tener mínimo 3 caracteres.',
            'nombre.max' => 'El nombre del área no puede superar los 255 caracteres.',
        ];
    }
}
