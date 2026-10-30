<?php

namespace App\Modules\Noticias\Requests;
use Illuminate\Foundation\Http\FormRequest;
class NewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }
    public function rules(): array
    {
        return [
            'trainingcenter_id' => ['required', 'exists:trainingcenters,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string', 'max:2000'],
            'estado' => ['required', 'in:Creado,Pendiente,Publicado'],
            'imagen' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ];
    }
    public function messages(): array
    {
        return [
            'trainingcenter_id.required' => 'Debes seleccionar un centro de formación.',
            'trainingcenter_id.exists' => 'El centro de formación seleccionado no es válido.',
            'titulo.required' => 'El título de la noticia es obligatorio.',
            'titulo.max' => 'El título no puede superar los 255 caracteres.',
            'descripcion.required' => 'La descripción de la noticia es obligatoria.',
            'descripcion.max' => 'La descripción no puede superar los 255 caracteres.',
            'estado.required' => 'Debes seleccionar un estado.',
            'estado.in' => 'El estado seleccionado no es válido.',
            'imagen.image' => 'La imagen debe ser válida.',
            'imagen.mimes' => 'La imagen debe ser PNG, JPG, JPEG o WEBP.',
            'imagen.max' => 'La imagen no puede superar los 2 MB.',
        ];
    }
    public function attributes(): array
    {
        return [
            'trainingcenter_id' => 'centro de formación',
            'titulo' => 'título',
            'descripcion' => 'descripción',
            'estado' => 'estado',
            'imagen' => 'imagen',
        ];
    }
}

