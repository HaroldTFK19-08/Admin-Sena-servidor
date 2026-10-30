<?php

namespace Database\Factories;

use App\Modules\Asignaciones\Models\Assignment;
use App\Modules\Equipos\Models\Equipment;
use App\Modules\Usuarios\Models\Apprentice;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Assignment>
 */
class AssignmentFactory extends Factory
{
    protected $model = Assignment::class;

    public function definition(): array
    {
        return [
            'equipment_id' => Equipment::factory(),
            'apprentice_id' => Apprentice::factory(),
            'fecha_asignacion' => now()->subDays(fake()->numberBetween(1, 45)),
            'fecha_devolucion' => null,
            'Estado' => 'Activo',
        ];
    }

    public function inactivo(): static
    {
        return $this->state([
            'Estado' => 'Inactivo',
            'fecha_devolucion' => now()->subDays(fake()->numberBetween(1, 10)),
        ]);
    }
}
