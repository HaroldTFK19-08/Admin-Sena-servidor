<?php

namespace Database\Factories;

use App\Modules\Fichas\Models\Course_group;
use App\Modules\Programas\Models\Program;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Course_group>
 */
class CourseGroupFactory extends Factory
{
    protected $model = Course_group::class;

    public function definition(): array
    {
        return [
            'program_id' => Program::factory(),
            'teacher_id' => Teacher::factory()->planta(),
            'codigo' => 'FIC-'.fake()->unique()->numerify('####'),
            'capacidad_aprendices' => fake()->numberBetween(15, 40),
        ];
    }
}
