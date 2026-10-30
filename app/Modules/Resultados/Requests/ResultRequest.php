<?php

namespace App\Modules\Resultados\Requests;

use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Foundation\Http\FormRequest;

class ResultRequest extends FormRequest
{
    use ResolvesApiUser;

    public function authorize(): bool
    {
        $user = $this->resolveApiUser($this);

        if (! $user && $this->apiPracticeMode()) {
            return true;
        }

        return auth()->user()?->rol === 'Admin' || $user?->rol === 'Admin';
    }

    public function rules(): array
    {
        return [
            'competition_id' => ['required', 'integer', 'exists:competitions,id'],
            'codigo' => ['nullable', 'string', 'max:255'],
            'nombre' => ['required', 'string', 'max:255'],
            'descripcion' => ['nullable', 'string'],
            'cantidad' => ['sometimes', 'nullable', 'integer', 'min:1', 'max:30'],
        ];
    }
}
