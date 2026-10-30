<?php

namespace App\Modules\Inscripciones\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AspirantRegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->rol === 'Aspirante';
    }

    public function rules(): array
    {
        $aspirante = auth()->user()?->aspirant;
        $archivo = ['file', 'mimes:pdf,jpg,jpeg,png', 'max:4096'];

        return [
            'offer_id' => ['required', 'integer', 'exists:offers,id'],
            'certificado_eps' => array_merge([
                $aspirante?->certificado_eps ? 'nullable' : 'required',
            ], $archivo),
            'certificado_bachiller' => array_merge([
                $aspirante?->certificado_bachiller ? 'nullable' : 'required',
            ], $archivo),
            'certificado_icfes' => array_merge([
                $aspirante?->certificado_icfes ? 'nullable' : 'required',
            ], $archivo),
        ];
    }

    public function messages(): array
    {
        return [
            'offer_id.required' => 'Debes seleccionar una oferta de formación.',
            'certificado_eps.required' => 'Debes adjuntar el certificado de EPS.',
            'certificado_bachiller.required' => 'Debes adjuntar el certificado de bachiller.',
            'certificado_icfes.required' => 'Debes adjuntar el certificado ICFES.',
        ];
    }
}
