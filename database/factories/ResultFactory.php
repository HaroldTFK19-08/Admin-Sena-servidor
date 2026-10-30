<?php

namespace Database\Factories;

use App\Modules\Competencias\Models\Competition;
use App\Modules\Resultados\Models\Result;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Result>
 */
class ResultFactory extends Factory
{
    protected $model = Result::class;

    public function definition(): array
    {
        return [
            'competition_id' => Competition::factory(),
            'codigo' => 'RA-'.fake()->unique()->numerify('####'),
            'nombre' => fake()->sentence(4),
            'descripcion' => fake()->paragraph(),
        ];
    }
}
