<?php

namespace App\Modules\Ambientes\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class EnvironmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            //
            'trainingcenter_id' => [
                'required',
                'integer',
                'exists:trainingcenters,id',
            ],
            'nombre' => [
                'required',
                'string',
                'min:3',
                'max:255',
            ],
            'capacidad' => [
                'required',
                'integer',
                'min:1',
            ],
            'tipo_ambiente' => [
                'required',
                Rule::in([
                    'Software',
                    'TICS',
                    'Laboratorio',
                    'Cocina',
                ]),
            ],
        ];
    }
    public function messages():array
    {
        return[
            'trainingcenter_id.required' => 'El centro de formación es obligatorio.',
            'trainingcenter_id.integer' => 'El centro de formación no es válido.',
            'trainingcenter_id.exists' => 'El centro de formación seleccionado no existe.',
            'nombre.required' => 'El nombre del ambiente es obligatorio.',
            'nombre.min' => 'El nombre debe tener mínimo 3 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'capacidad.required' => 'La capacidad es obligatoria.',
            'capacidad.integer' => 'La capacidad debe ser un número entero.',
            'capacidad.min' => 'La capacidad debe ser mínimo de 1.',
            'tipo_ambiente.required' => 'El tipo de ambiente es obligatorio.',
            'tipo_ambiente.in' => 'El tipo de ambiente seleccionado no es válido.',
        ];
    }
}
