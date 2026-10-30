<?php

namespace Database\Factories;

use App\Modules\Ambientes\Models\Environment;
use App\Modules\Centros\Models\Trainingcenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Environment>
 */
class EnvironmentFactory extends Factory
{
    protected $model = Environment::class;

    public function definition(): array
    {
        return [
            'trainingcenter_id' => Trainingcenter::factory(),
            'nombre' => fake()->unique()->words(2, true).' '.fake()->randomElement(['Tecnológico', 'Práctico', 'Simulación']),
            'capacidad' => fake()->numberBetween(10, 40),
            'tipo_ambiente' => fake()->randomElement(['Software', 'TICS', 'Laboratorio', 'Cocina']),
        ];
    }
}
