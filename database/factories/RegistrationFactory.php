<?php

namespace Database\Factories;

use App\Modules\Inscripciones\Models\Registration;
use App\Modules\Ofertas\Models\Offer;
use App\Modules\Usuarios\Models\Aspirant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Registration>
 */
class RegistrationFactory extends Factory
{
    protected $model = Registration::class;

    public function definition(): array
    {
        return [
            'aspirant_id' => Aspirant::factory(),
            'offer_id' => Offer::factory(),
            'estado' => fake()->randomElement(Registration::ESTADOS),
            'Fecha_inscripcion' => now()->subDays(fake()->numberBetween(1, 60)),
        ];
    }

    public function registrado(): static
    {
        return $this->state(['estado' => 'Registrado']);
    }

    public function enProceso(): static
    {
        return $this->state(['estado' => 'En proceso']);
    }

    public function seleccionado(): static
    {
        return $this->state(['estado' => 'Seleccionado']);
    }

    public function rechazado(): static
    {
        return $this->state(['estado' => 'Rechazado']);
    }

    public function cancelada(): static
    {
        return $this->state(['estado' => 'Cancelada']);
    }
}
