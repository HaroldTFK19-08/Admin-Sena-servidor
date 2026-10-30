<?php

namespace Database\Factories;

use App\Modules\Usuarios\Models\Aspirant;
use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Aspirant>
 */
class AspirantFactory extends Factory
{
    protected $model = Aspirant::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory()->aspirant(),
            'eps' => fake()->randomElement(['SURA', 'Sanitas', 'Nueva EPS', 'Salud Total', 'Compensar', 'Coosalud', 'Emssanar']),
            'certificado_eps' => null,
            'certificado_bachiller' => null,
            'certificado_icfes' => null,
        ];
    }
}
