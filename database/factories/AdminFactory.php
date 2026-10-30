<?php

namespace Database\Factories;

use App\Modules\Usuarios\Models\Admin;
use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Admin>
 */
class AdminFactory extends Factory
{
    protected $model = Admin::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->admin(),
            'cargo' => fake()->randomElement(['Administrador', 'Coordinador', 'Gestor']),
            'ubicacion_oficina' => fake()->randomElement(['Principal', 'Academia', 'Administración']),
            'direccion' => fake()->address(),
        ];
    }
}
