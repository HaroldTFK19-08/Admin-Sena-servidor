<?php

namespace App\Modules\Usuarios\Requests;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerfilAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_1' => ['required', 'string', 'max:20'],
            'nombre_2' => ['nullable', 'string', 'max:20'],
            'apellido_1' => ['required', 'string', 'max:20'],
            'apellido_2' => ['nullable', 'string', 'max:20'],
            'foto_perfil' => ['nullable', 'image', 'mimes:png', 'max:2048'],
            'tipo_identificacion' => ['required', Rule::in(['CC', 'TI', 'CE', 'PPT'])],
            'identificacion' => ['required', 'string', Rule::unique('users', 'identificacion')->ignore($this->user()?->id)],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($this->user()?->id)],
            'cargo' => ['required', 'string', 'max:100'],
            'ubicacion_oficina' => ['required', 'string', 'max:150'],
            'direccion' => ['nullable', 'string', 'max:255'],
        ];
    }
    public function messages(): array
    {
        return [
            'nombre_1.required' => 'El primer nombre es obligatorio.',
            'nombre_1.max' => 'El primer nombre no puede superar los 20 caracteres.',
            'nombre_2.max' => 'El segundo nombre no puede superar los 20 caracteres.',
            'apellido_1.required' => 'El primer apellido es obligatorio.',
            'apellido_1.max' => 'El primer apellido no puede superar los 20 caracteres.',
            'apellido_2.max' => 'El segundo apellido no puede superar los 20 caracteres.',
            'foto_perfil.image' => 'La foto de perfil debe ser una imagen válida.',
            'foto_perfil.mimes' => 'La foto de perfil debe estar en formato PNG.',
            'foto_perfil.max' => 'La foto de perfil no puede superar los 2 MB.',
            'tipo_identificacion.required' => 'El tipo de identificación es obligatorio.',
            'tipo_identificacion.in' => 'El tipo de identificación seleccionado no es válido.',
            'identificacion.required' => 'El número de identificación es obligatorio.',
            'identificacion.unique' => 'El número de identificación ya está registrado.',
            'email.required' => 'El correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico no tiene un formato válido.',
            'email.unique' => 'El correo electrónico ya está registrado.',
            'cargo.required' => 'El cargo es obligatorio.',
            'cargo.max' => 'El cargo no puede superar los 100 caracteres.',
            'ubicacion_oficina.required' => 'La ubicación de la oficina es obligatoria.',
            'ubicacion_oficina.max' => 'La ubicación de la oficina no puede superar los 150 caracteres.',
            'direccion.max' => 'La dirección no puede superar los 255 caracteres.',
        ];
    }
}
