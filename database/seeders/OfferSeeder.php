<?php

namespace Database\Seeders;

use App\Modules\Ofertas\Models\Offer;
use App\Modules\Programas\Models\Program;
use App\Modules\Usuarios\Models\Admin;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([AdminSeeder::class, ProgramSeeder::class]);

        $admin = Admin::firstOrFail();
        $programas = Program::orderBy('id')->get();
        $fechas = [
            now()->subDays(30),
            now()->subDays(15),
            now()->addDays(10),
        ];

        foreach ($programas as $index => $programa) {
            $lanzamiento = $fechas[$index % count($fechas)];
            Offer::updateOrCreate(
                ['program_id' => $programa->id],
                [
                    'admin_id' => $admin->id,
                    'fecha_lanzamiento' => $lanzamiento->toDateString(),
                    'capacidad' => 30,
                    'fecha_convocatoria' => $lanzamiento->copy()->addDays(3)->toDateString(),
                    'fecha_fin_convocatoria' => $lanzamiento->copy()->addDays(30)->toDateString(),
                    'fecha_primera_prueba' => $lanzamiento->copy()->addDays(50)->toDateString(),
                    'fecha_segunda_prueba' => $lanzamiento->copy()->addDays(75)->toDateString(),
                    'fecha_seleccionados' => $lanzamiento->copy()->addDays(100)->toDateString(),
                    'imagen' => null,
                    'publicada' => true,
                ]
            );
        }
    }
}
