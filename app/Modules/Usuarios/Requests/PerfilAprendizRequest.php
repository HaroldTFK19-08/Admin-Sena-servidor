<?php

namespace App\Modules\Usuarios\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerfilAprendizRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        // Al editar, se ignora el usuario actual en las reglas unique
        $userId = $this->route('aprendiz')?->user_id;

        return [
            // Datos del usuario (tabla users)
            'nombre_1' => ['required', 'string', 'max:20'],
            'nombre_2' => ['nullable', 'string', 'max:20'],
            'apellido_1' => ['required', 'string', 'max:20'],
            'apellido_2' => ['nullable', 'string', 'max:20'],
            'foto_perfil' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'tipo_identificacion' => ['required', Rule::in(['CC', 'TI', 'CE', 'PPT'])],
            'identificacion' => ['required', 'string', Rule::unique('users', 'identificacion')->ignore($userId)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($userId)],
            'password' => [$userId ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            // Datos del aprendiz (tabla apprentices)
            'course_group_id' => ['required', 'exists:course_groups,id'],
            'estado' => ['required', Rule::in(['Activo', 'Retirado', 'Certificado'])],
            'fecha_inicio' => ['required', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'max' => 'El campo :attribute no puede superar :max caracteres.',
            'min' => 'El campo :attribute debe tener mínimo :min caracteres.',
            'unique' => 'El :attribute ya está registrado.',
            'exists' => 'La :attribute seleccionada no existe.',
            'confirmed' => 'Las contraseñas no coinciden.',
            'after_or_equal' => 'La fecha de fin debe ser igual o posterior a la de inicio.',
        ];
    }
}
