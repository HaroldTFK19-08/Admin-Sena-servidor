<?php

namespace App\Modules\Inscripciones\Requests;

use App\Modules\Inscripciones\Models\Registration;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth()->user()?->rol === 'Admin';
    }

    public function rules(): array
    {
        return [
            'aspirant_id' => ['required', 'integer', 'exists:aspirants,id'],
            'offer_id' => ['required', 'integer', 'exists:offers,id'],
            'estado' => ['required', Rule::in(Registration::ESTADOS)],
            'Fecha_inscripcion' => ['nullable', 'date'],
        ];
    }
}
