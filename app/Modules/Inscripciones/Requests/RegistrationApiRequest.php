<?php

namespace App\Modules\Inscripciones\Requests;

use App\Modules\Inscripciones\Models\Registration;
use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegistrationApiRequest extends FormRequest
{
    use ResolvesApiUser;

    public function authorize(): bool
    {
        $user = $this->resolveApiUser($this);

        if (! $user && $this->apiPracticeMode()) {
            return true;
        }

        return in_array($user?->rol, ['Admin', 'Aspirante'], true);
    }

    public function rules(): array
    {
        $user = $this->resolveApiUser($this);
        $admin = ! $user || $user->rol === 'Admin';

        return [
            'aspirant_id' => [$admin ? 'required' : 'nullable', 'integer', 'exists:aspirants,id'],
            'offer_id' => ['required', 'integer', 'exists:offers,id'],
            'estado' => [$admin ? 'required' : 'nullable', Rule::in(Registration::ESTADOS)],
            'Fecha_inscripcion' => ['nullable', 'date'],
        ];
    }
}
