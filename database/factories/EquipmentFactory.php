<?php

namespace Database\Factories;

use App\Modules\Ambientes\Models\Environment;
use App\Modules\Equipos\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Equipment>
 */
class EquipmentFactory extends Factory
{
    protected $model = Equipment::class;

    public function definition(): array
    {
        return [
            'environment_id' => Environment::factory(),
            'name' => fake()->unique()->words(2, true),
            'brand' => fake()->randomElement(['SENA', 'AULA', 'Tecnología', 'Genérico']),
            'equipment_type' => fake()->randomElement(['Equipo de cómputo', 'Instrumento', 'Herramienta', 'Simulador']),
        ];
    }
}
