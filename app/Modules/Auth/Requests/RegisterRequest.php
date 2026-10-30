<?php

namespace App\Modules\Auth\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre_1' => ['required', 'string', 'max:20', 'regex:/^[\pL\s]+$/u'],
            'nombre_2' => ['nullable', 'string', 'max:20', 'regex:/^[\pL\s]+$/u'],
            'apellido_1' => ['required', 'string', 'max:20', 'regex:/^[\pL\s]+$/u'],
            'apellido_2' => ['nullable', 'string', 'max:20', 'regex:/^[\pL\s]+$/u'],
            'foto_perfil' => [
                'nullable',
                'image',
                'mimes:png',
                'max:2048',
            ],
            'tipo_identificacion' => [
                'required',
                'in:CC,TI,CE,PPT',
            ],
            'identificacion' => [
                'required',
                'string',
                'unique:users,identificacion',
            ],
            'email' => [
                'required',
                'email',
                'unique:users,email',
            ],
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
            ],
            'rol' => [
                'required',
                'in:aprendiz,instructor,aspirante',
            ],

            // Atributos del aprendiz (tabla apprentices)
            'course_group_id' => ['required_if:rol,aprendiz', 'nullable', 'integer', 'exists:course_groups,id'],

            // Atributos del instructor (tabla teachers)
            'area_id' => ['required_if:rol,instructor', 'nullable', 'integer', 'exists:areas,id'],
            'especialidad' => ['required_if:rol,instructor', 'nullable', 'string', 'max:150'],
            'tipo_instructor' => ['required_if:rol,instructor', 'nullable', 'in:Planta,Contratista'],

            // Comunes a aprendiz e instructor
            'fecha_inicio' => ['required_if:rol,aprendiz,instructor', 'nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.',
            'string' => 'El campo :attribute debe contener texto.',
            'max' => 'El campo :attribute no puede superar :max caracteres.',
            'min' => 'El campo :attribute debe tener mínimo :min caracteres.',
            'required_if' => 'El campo :attribute es obligatorio para el rol seleccionado.',
            'exists' => 'El :attribute seleccionado no existe.',
            'in' => 'El valor de :attribute no es válido.',
            'date' => 'El campo :attribute no tiene un formato de fecha válido.',
            'after_or_equal' => 'La fecha de finalización debe ser posterior o igual a la fecha de inicio.',
            'integer' => 'El campo :attribute no es válido.',
            'regex' => 'El campo :attribute solo puede contener letras.',
            'image' => 'La foto de perfil debe ser una imagen válida.',
            'mimes' => 'La foto de perfil debe estar en formato PNG.',
            'email' => 'El correo electrónico no tiene un formato válido.',
            'unique' => 'El :attribute ya está registrado.',
            'confirmed' => 'Las contraseñas no coinciden.',
        ];
    }
    public function attributes(): array
    {
        return [
            'nombre_1' => 'primer nombre',
            'nombre_2' => 'segundo nombre',
            'apellido_1' => 'primer apellido',
            'apellido_2' => 'segundo apellido',
            'foto_perfil' => 'foto de perfil',
            'tipo_identificacion' => 'tipo de identificación',
            'identificacion' => 'número de identificación',
            'email' => 'correo electrónico',
            'password' => 'contraseña',
            'rol' => 'rol',
            'course_group_id' => 'ficha',
            'area_id' => 'área',
            'especialidad' => 'especialidad',
            'tipo_instructor' => 'tipo de instructor',
            'fecha_inicio' => 'fecha de inicio',
            'fecha_fin' => 'fecha de finalización',
        ];
    }
}

