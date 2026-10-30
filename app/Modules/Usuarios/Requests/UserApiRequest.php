<?php

namespace App\Modules\Usuarios\Requests;

use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserApiRequest extends FormRequest
{
    use ResolvesApiUser;

    public function authorize(): bool
    {
        $user = $this->resolveApiUser($this);

        if (! $user && $this->apiPracticeMode()) {
            return true;
        }

        return $user?->rol === 'Admin';
    }

    public function rules(): array
    {
        $usuario = $this->route('usuario');
        $actualizando = $usuario !== null;

        // Al crear, los atributos del rol son obligatorios. Al editar solo se validan
        // si el formulario los envía (así un formulario que solo edita datos básicos sigue funcionando).
        $conPerfil = ! $actualizando || $this->hasAny([
            'course_group_id', 'estado', 'area_id', 'especialidad', 'tipo_instructor', 'fecha_inicio', 'fecha_fin',
        ]);
        $siAprendiz = $conPerfil ? 'required_if:rol,Aprendiz' : 'nullable';
        $siInstructor = $conPerfil ? 'required_if:rol,Instructor' : 'nullable';
        $siAmbos = $conPerfil ? 'required_if:rol,Aprendiz,Instructor' : 'nullable';

        return [
            'nombre_1' => ['required', 'string', 'max:20'],
            'nombre_2' => ['nullable', 'string', 'max:20'],
            'apellido_1' => ['required', 'string', 'max:20'],
            'apellido_2' => ['nullable', 'string', 'max:20'],
            'tipo_identificacion' => ['required', Rule::in(['CC', 'TI', 'CE', 'PPT'])],
            'identificacion' => ['required', 'string', 'max:255', Rule::unique('users', 'identificacion')->ignore($usuario)],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($usuario)],
            'rol' => ['required', Rule::in(['Admin', 'Instructor', 'Aprendiz', 'Aspirante'])],
            'password' => [$actualizando ? 'nullable' : 'required', 'string', 'min:8', 'confirmed'],
            'password_confirmation' => [$actualizando ? 'nullable' : 'required', 'string', 'min:8'],
            'foto_perfil' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],

            // Atributos del aprendiz (tabla apprentices)
            'course_group_id' => [$siAprendiz, 'nullable', 'integer', 'exists:course_groups,id'],
            'estado' => ['nullable', Rule::in(['Activo', 'Retirado', 'Certificado'])],

            // Atributos del instructor (tabla teachers)
            'area_id' => [$siInstructor, 'nullable', 'integer', 'exists:areas,id'],
            'especialidad' => [$siInstructor, 'nullable', 'string', 'max:150'],
            'tipo_instructor' => [$siInstructor, 'nullable', Rule::in(['Planta', 'Contratista'])],

            // Comunes a aprendiz e instructor
            'fecha_inicio' => [$siAmbos, 'nullable', 'date'],
            'fecha_fin' => ['nullable', 'date', 'after_or_equal:fecha_inicio'],
        ];
    }

    public function messages(): array
    {
        return [
            'course_group_id.required_if' => 'Debes seleccionar la ficha del aprendiz.',
            'course_group_id.exists' => 'La ficha seleccionada no existe.',
            'area_id.required_if' => 'Debes seleccionar el área del instructor.',
            'area_id.exists' => 'El área seleccionada no existe.',
            'especialidad.required_if' => 'La especialidad del instructor es obligatoria.',
            'tipo_instructor.required_if' => 'El tipo de instructor es obligatorio.',
            'tipo_instructor.in' => 'El tipo de instructor seleccionado no es válido.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'fecha_inicio.required_if' => 'La fecha de inicio es obligatoria para este rol.',
            'fecha_fin.after_or_equal' => 'La fecha de finalización debe ser posterior o igual a la fecha de inicio.',
        ];
    }
}
