<?php

namespace App\Modules\Usuarios\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PerfilAspiranteRequest extends FormRequest
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
            'eps' => ['required', Rule::in(['SURA', 'Sanitas', 'Nueva EPS', 'Salud Total', 'Compensar', 'Coosalud', 'Emssanar'])],
            'certificado_eps' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:4096'],
            'certificado_bachiller' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:4096'],
            'certificado_icfes' => ['nullable', 'file', 'mimes:pdf,png,jpg,jpeg', 'max:4096'],
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
            'eps.required' => 'Debes seleccionar una EPS.',
            'eps.in' => 'La EPS seleccionada no es válida.',
            'certificado_eps.file' => 'El certificado EPS debe ser un archivo válido.',
            'certificado_eps.mimes' => 'El certificado EPS debe ser PDF, PNG, JPG o JPEG.',
            'certificado_eps.max' => 'El certificado EPS no puede superar los 4 MB.',
            'certificado_bachiller.file' => 'El certificado de bachiller debe ser un archivo válido.',
            'certificado_bachiller.mimes' => 'El certificado de bachiller debe ser PDF, PNG, JPG o JPEG.',
            'certificado_bachiller.max' => 'El certificado de bachiller no puede superar los 4 MB.',
            'certificado_icfes.file' => 'El certificado ICFES debe ser un archivo válido.',
            'certificado_icfes.mimes' => 'El certificado ICFES debe ser PDF, PNG, JPG o JPEG.',
            'certificado_icfes.max' => 'El certificado ICFES no puede superar los 4 MB.',
        ];
    }
}