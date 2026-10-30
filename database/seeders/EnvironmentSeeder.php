<?php

namespace Database\Seeders;

use App\Modules\Ambientes\Models\Environment;
use App\Modules\Centros\Models\Trainingcenter;
use Illuminate\Database\Seeder;

class EnvironmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(TrainingCenterSeeder::class);

        $centro = Trainingcenter::query()->firstOrFail();
        $ambientes = [
            ['nombre' => 'Laboratorio de software', 'capacidad' => 24, 'tipo_ambiente' => 'Software'],
            ['nombre' => 'Laboratorio de redes', 'capacidad' => 20, 'tipo_ambiente' => 'TICS'],
            ['nombre' => 'Taller práctico', 'capacidad' => 18, 'tipo_ambiente' => 'Laboratorio'],
            ['nombre' => 'Cocina didáctica', 'capacidad' => 16, 'tipo_ambiente' => 'Cocina'],
        ];

        foreach ($ambientes as $ambiente) {
            Environment::updateOrCreate(
                ['trainingcenter_id' => $centro->id, 'nombre' => $ambiente['nombre']],
                ['capacidad' => $ambiente['capacidad'], 'tipo_ambiente' => $ambiente['tipo_ambiente']]
            );
        }
    }
}
