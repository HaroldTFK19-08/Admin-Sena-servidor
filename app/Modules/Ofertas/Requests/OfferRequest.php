<?php

namespace App\Modules\Ofertas\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OfferRequest extends FormRequest
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
            'program_id' => ['required', 'exists:programs,id'],
            'fecha_lanzamiento' => ['required', 'date'],
            'capacidad' => ['required', 'integer', 'min:1'],
            'fecha_convocatoria' => ['required', 'date', 'after_or_equal:fecha_lanzamiento'],
            'fecha_fin_convocatoria' => ['required', 'date', 'after:fecha_convocatoria'],
            'fecha_primera_prueba' => ['required', 'date', 'after:fecha_fin_convocatoria'],
            'fecha_segunda_prueba' => ['nullable', 'date', 'after:fecha_primera_prueba'],
            'fecha_seleccionados' => ['required', 'date', 'after_or_equal:fecha_segunda_prueba'],
            'imagen' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
            'publicada' => ['sometimes', 'boolean'],
        ];
    }
    public function messages(): array
    {
        return [
            'required' => 'El campo :attribute es obligatorio.', 
            'exists' => 'El :attribute seleccionado no existe.', 
            'date' => 'El campo :attribute debe contener una fecha válida.', 
            'integer' => 'El campo :attribute debe ser un número entero.', 
            'min' => 'El campo :attribute debe ser mínimo :min.', 
            'after' => 'El campo :attribute debe ser posterior a :date.', 
            'after_or_equal' => 'El campo :attribute debe ser igual o posterior a :date.', 
            'image' => 'El archivo debe ser una imagen válida.', 
            'mimes' => 'La imagen debe ser PNG, JPG, JPEG o WEBP.', 
            'max.file' => 'La imagen no puede superar los 2 MB.',
            'max' => 'El campo :attribute no puede superar :max.',
        ];
    }
    public function attributes(): array
    {
        return [
            'program_id' => 'programa', 
            'fecha_lanzamiento' => 'fecha de lanzamiento', 
            'capacidad' => 'capacidad', 
            'fecha_convocatoria' => 'fecha de convocatoria', 
            'fecha_fin_convocatoria' => 'fecha de cierre de convocatoria', 
            'fecha_primera_prueba' => 'fecha de primera prueba', 
            'fecha_segunda_prueba' => 'fecha de segunda prueba', 
            'fecha_seleccionados' => 'fecha de seleccionados', 
            'imagen' => 'imagen',];
    }
}
