<?php

namespace Database\Factories;

use App\Modules\Centros\Models\Trainingcenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Trainingcenter>
 */
class TrainingcenterFactory extends Factory
{
    protected $model = Trainingcenter::class;

    public function definition(): array
    {
        return [
            'nombre' => fake()->unique()->company().' SENA',
            'direccion' => fake()->address(),
        ];
    }
}
