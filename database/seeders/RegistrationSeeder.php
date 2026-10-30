<?php

namespace Database\Seeders;

use App\Modules\Inscripciones\Models\Registration;
use App\Modules\Ofertas\Models\Offer;
use App\Modules\Usuarios\Models\Aspirant;
use Illuminate\Database\Seeder;

class RegistrationSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([AspirantSeeder::class, OfferSeeder::class]);

        $aspirantes = Aspirant::orderBy('id')->get();
        $ofertas = Offer::orderBy('id')->get();
        $estados = ['En proceso', 'Registrado', 'Seleccionado'];

        foreach ($aspirantes as $index => $aspirante) {
            Registration::updateOrCreate(
                ['aspirant_id' => $aspirante->id, 'offer_id' => $ofertas[$index % $ofertas->count()]->id],
                [
                    'estado' => $estados[$index % count($estados)],
                    'Fecha_inscripcion' => now()->subDays(10 - ($index * 2))->toDateTimeString(),
                ]
            );
        }
    }
}
