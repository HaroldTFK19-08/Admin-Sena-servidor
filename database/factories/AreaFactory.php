<?php

namespace Database\Factories;

use App\Modules\Areas\Models\Area;
use App\Modules\Centros\Models\Trainingcenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Area>
 */
class AreaFactory extends Factory
{
    protected $model = Area::class;

    public function definition(): array
    {
        return [
            'trainingcenter_id' => Trainingcenter::factory(),
            'nombre' => fake()->unique()->words(2, true),
        ];
    }
}
