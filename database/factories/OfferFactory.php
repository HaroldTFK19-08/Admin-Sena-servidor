<?php

namespace Database\Factories;

use App\Modules\Ofertas\Models\Offer;
use App\Modules\Programas\Models\Program;
use App\Modules\Usuarios\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    public function definition(): array
    {
        $lanzamiento = now()->subDays(fake()->numberBetween(10, 60));

        return [
            'admin_id' => Admin::factory(),
            'program_id' => Program::factory(),
            'fecha_lanzamiento' => $lanzamiento->toDateString(),
            'capacidad' => fake()->numberBetween(15, 60),
            'fecha_convocatoria' => $lanzamiento->copy()->addDays(5)->toDateString(),
            'fecha_fin_convocatoria' => $lanzamiento->copy()->addDays(40)->toDateString(),
            'fecha_primera_prueba' => $lanzamiento->copy()->addDays(60)->toDateString(),
            'fecha_segunda_prueba' => $lanzamiento->copy()->addDays(90)->toDateString(),
            'fecha_seleccionados' => $lanzamiento->copy()->addDays(120)->toDateString(),
            'imagen' => null,
            'publicada' => fake()->boolean(),
        ];
    }

    public function publicada(): static
    {
        return $this->state([
            'publicada' => true,
            'fecha_lanzamiento' => now()->subDays(10)->toDateString(),
            'fecha_convocatoria' => now()->subDays(5)->toDateString(),
            'fecha_fin_convocatoria' => now()->addDays(30)->toDateString(),
            'fecha_primera_prueba' => now()->addDays(50)->toDateString(),
            'fecha_segunda_prueba' => now()->addDays(80)->toDateString(),
            'fecha_seleccionados' => now()->addDays(110)->toDateString(),
        ]);
    }

    public function borrador(): static
    {
        return $this->state(['publicada' => false]);
    }
}
