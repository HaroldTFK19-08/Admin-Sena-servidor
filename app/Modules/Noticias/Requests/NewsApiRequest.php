<?php

namespace App\Modules\Noticias\Requests;

use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class NewsApiRequest extends FormRequest
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
        return [
            'trainingcenter_id' => ['required', 'integer', 'exists:trainingcenters,id'],
            'titulo' => ['required', 'string', 'max:255'],
            'descripcion' => ['required', 'string', 'max:2000'],
            'estado' => ['required', Rule::in(['Creado', 'Pendiente', 'Publicado'])],
            'categoria' => ['required', Rule::in(['Tecnologia', 'Educacion', 'Deporte', 'Politica', 'Artistica', 'Diseño'])],
            'publicada' => ['sometimes', 'boolean'],
            'imagen' => ['nullable', 'image', 'mimes:png,jpg,jpeg,webp', 'max:2048'],
        ];
    }
}
