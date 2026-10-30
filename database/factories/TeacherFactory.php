<?php

namespace Database\Factories;

use App\Modules\Areas\Models\Area;
use App\Modules\Usuarios\Models\Teacher;
use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Teacher>
 */
class TeacherFactory extends Factory
{
    protected $model = Teacher::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->instructor(),
            'area_id' => Area::factory(),
            'especialidad' => fake()->jobTitle(),
            'tipo_instructor' => 'Planta',
            'fecha_inicio' => now()->subYears(fake()->numberBetween(1, 8))->toDateString(),
            'fecha_fin' => null,
        ];
    }

    public function planta(): static
    {
        return $this->state(['tipo_instructor' => 'Planta']);
    }

    public function contratista(): static
    {
        return $this->state(['tipo_instructor' => 'Contratista']);
    }
}
