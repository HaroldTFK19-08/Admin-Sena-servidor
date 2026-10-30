<?php

namespace App\Modules\Asignaciones\Requests;

use App\Support\Concerns\ResolvesApiUser;
use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
{
    use ResolvesApiUser;

    public function authorize(): bool
    {
        if ($this->apiPracticeMode() && ! auth()->check() && ! $this->resolveApiUser($this)) {
            return true;
        }

        return auth()->check() || $this->resolveApiUser($this) !== null;
    }

    public function rules(): array
    {
        return [
            'equipment_id' => ['required', 'integer', 'exists:equipments,id'],
            'apprentice_id' => ['required', 'integer', 'exists:apprentices,id'],
            'fecha_asignacion' => ['required', 'date'],
            'fecha_devolucion' => ['nullable', 'date', 'after_or_equal:fecha_asignacion'],
            'Estado' => ['required', 'in:Activo,Inactivo'],
        ];
    }

    public function messages(): array
    {
        return [
            'equipment_id.required' => 'El equipo es obligatorio.',
            'equipment_id.exists' => 'El equipo seleccionado no existe.',
            'apprentice_id.required' => 'El aprendiz es obligatorio.',
            'apprentice_id.exists' => 'El aprendiz seleccionado no existe.',
            'fecha_asignacion.required' => 'La fecha de asignación es obligatoria.',
            'fecha_asignacion.date' => 'La fecha de asignación no tiene un formato válido.',
            'fecha_devolucion.date' => 'La fecha de devolución no tiene un formato válido.',
            'fecha_devolucion.after_or_equal' => 'La fecha de devolución no puede ser anterior a la asignación.',
            'Estado.required' => 'El estado es obligatorio.',
            'Estado.in' => 'El estado debe ser Activo o Inactivo.',
        ];
    }
}
