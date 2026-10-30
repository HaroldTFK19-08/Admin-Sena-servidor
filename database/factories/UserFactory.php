<?php

namespace Database\Factories;

use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'nombre_1' => fake()->firstName(),
            'nombre_2' => fake()->optional()->firstName(),
            'apellido_1' => fake()->lastName(),
            'apellido_2' => fake()->optional()->lastName(),
            'tipo_identificacion' => fake()->randomElement(['CC', 'TI', 'CE', 'PPT']),
            'identificacion' => fake()->unique()->numerify('##########'),
            'email' => fake()->unique()->safeEmail(),
            'password' => Hash::make('12345678'),
            'rol' => 'Admin',
        ];
    }

    public function admin(): static
    {
        return $this->state(['rol' => 'Admin']);
    }

    public function instructor(): static
    {
        return $this->state(['rol' => 'Instructor']);
    }

    public function apprentice(): static
    {
        return $this->state(['rol' => 'Aprendiz']);
    }

    public function aspirant(): static
    {
        return $this->state(['rol' => 'Aspirante']);
    }
}
