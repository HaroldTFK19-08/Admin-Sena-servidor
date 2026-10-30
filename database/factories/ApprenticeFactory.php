<?php

namespace Database\Factories;

use App\Modules\Usuarios\Models\Apprentice;
use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Apprentice>
 */
class ApprenticeFactory extends Factory
{
    protected $model = Apprentice::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->apprentice(),
            'course_group_id' => CourseGroupFactory::new(),
            'estado' => 'Activo',
            'fecha_inicio' => now()->subMonths(fake()->numberBetween(1, 12))->toDateString(),
            'fecha_fin' => now()->addMonths(fake()->numberBetween(4, 12))->toDateString(),
        ];
    }

    public function retirado(): static
    {
        return $this->state(['estado' => 'Retirado']);
    }

    public function certificado(): static
    {
        return $this->state(['estado' => 'Certificado']);
    }
}
