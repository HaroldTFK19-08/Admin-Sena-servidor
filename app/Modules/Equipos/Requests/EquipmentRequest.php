<?php

namespace App\Modules\Equipos\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EquipmentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'environment_id' => ['required', 'integer', 'exists:environments,id'],
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'brand' => ['nullable', 'string', 'max:100'],
            'equipment_type' => ['required', 'string', 'min:3', 'max:100'],
        ];
    }

    public function messages(): array
    {
        return [
            'environment_id.required' => 'El ambiente es obligatorio.',
            'environment_id.integer' => 'El ambiente no es válido.',
            'environment_id.exists' => 'El ambiente seleccionado no existe.',
            'name.required' => 'El nombre del equipo es obligatorio.',
            'name.min' => 'El nombre del equipo debe tener mínimo 3 caracteres.',
            'name.max' => 'El nombre del equipo no puede superar los 255 caracteres.',
            'brand.max' => 'La marca no puede superar los 100 caracteres.',
            'equipment_type.required' => 'El tipo de equipo es obligatorio.',
            'equipment_type.min' => 'El tipo de equipo debe tener mínimo 3 caracteres.',
            'equipment_type.max' => 'El tipo de equipo no puede superar los 100 caracteres.',
        ];
    }
}
