<?php

namespace App\Modules\Centros\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TrainingcentersRequest extends FormRequest
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
            'nombre' => [
                'required',
                'string',
                'min:5',
                'max:255',
                'regex:/^[\pL\s]+$/u',
            ],
            'direccion' => [
                'required',
                'string',
                'min:10',
                'max:255',
            ],
        ];
    }
    public function messages():array{
        return [
            'nombre.required' => 'El nombre del centro es obligatorio.',
            'nombre.string' => 'El nombre debe ser texto.',
            'nombre.min' => 'El nombre debe tener mínimo 5 caracteres.',
            'nombre.max' => 'El nombre no puede superar los 255 caracteres.',
            'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
            'direccion.required' => 'La dirección es obligatoria.',
            'direccion.string' => 'La dirección debe ser texto.',
            'direccion.min' => 'La dirección debe tener mínimo 10 caracteres.',
            'direccion.max' => 'La dirección no puede superar los 255 caracteres.',
        ];
    }
}
