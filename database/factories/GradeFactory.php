<?php

namespace Database\Factories;

use App\Modules\Resultados\Models\Grade;
use App\Modules\Resultados\Models\Result;
use App\Modules\Usuarios\Models\Apprentice;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Grade>
 */
class GradeFactory extends Factory
{
    protected $model = Grade::class;

    public function definition(): array
    {
        return [
            'result_id' => Result::factory(),
            'apprentice_id' => Apprentice::factory(),
            'teacher_id' => Teacher::factory(),
            'estado_resultado' => fake()->randomElement(Grade::ESTADOS),
            'comentarios' => fake()->optional()->sentence(),
        ];
    }

    public function aprobado(): static
    {
        return $this->state(['estado_resultado' => 'Aprobado']);
    }

    public function reprobado(): static
    {
        return $this->state(['estado_resultado' => 'Reprobado']);
    }

    public function pendiente(): static
    {
        return $this->state(['estado_resultado' => 'Pendiente']);
    }
}
