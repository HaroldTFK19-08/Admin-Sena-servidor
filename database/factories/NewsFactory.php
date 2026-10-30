<?php

namespace Database\Factories;

use App\Modules\Centros\Models\Trainingcenter;
use App\Modules\Noticias\Models\News;
use App\Modules\Usuarios\Models\Admin;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<News>
 */
class NewsFactory extends Factory
{
    protected $model = News::class;

    public function definition(): array
    {
        return [
            'trainingcenter_id' => Trainingcenter::factory(),
            'admin_id' => Admin::factory(),
            'titulo' => fake()->sentence(6),
            'descripcion' => fake()->paragraph(),
            'imagen' => null,
            'estado' => fake()->randomElement(['Creado', 'Pendiente', 'Publicado']),
            'categoria' => fake()->randomElement(['Tecnologia', 'Educacion', 'Deporte', 'Politica', 'Artistica', 'Diseño']),
            'publicada' => fake()->boolean(),
        ];
    }

    public function publicada(): static
    {
        return $this->state([
            'estado' => 'Publicado',
            'publicada' => true,
        ]);
    }

    public function borrador(): static
    {
        return $this->state([
            'estado' => 'Creado',
            'publicada' => false,
        ]);
    }
}
