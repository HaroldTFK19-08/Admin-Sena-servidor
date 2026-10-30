<?php

namespace Database\Factories;

use App\Modules\Competencias\Models\Competition;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Competition>
 */
class CompetitionFactory extends Factory
{
    protected $model = Competition::class;

    public function definition(): array
    {
        $inicio = now()->subMonths(fake()->numberBetween(1, 6));

        return [
            'teacher_id' => Teacher::factory(),
            'codigo' => 'COMP-'.fake()->unique()->numerify('####'),
            'nombre' => fake()->unique()->jobTitle(),
            'descripcion' => fake()->paragraph(),
            'fecha_inicio' => $inicio->toDateString(),
            'fecha_fin' => $inicio->copy()->addMonths(fake()->numberBetween(6, 18))->toDateString(),
        ];
    }
}
