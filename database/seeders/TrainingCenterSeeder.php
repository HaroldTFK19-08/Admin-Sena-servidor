<?php

namespace Database\Seeders;

use App\Modules\Centros\Models\Trainingcenter;
use Illuminate\Database\Seeder;

class TrainingCenterSeeder extends Seeder
{
    public function run(): void
    {
        $centros = [
            ['nombre' => 'Centro de Formación Tecnológica SENA', 'direccion' => 'Carrera 7 # 100-20, Bogotá'],
            ['nombre' => 'Centro de Diseño e Innovación', 'direccion' => 'Avenida 26 # 50-20, Medellín'],
            ['nombre' => 'Centro de Administración y Servicio', 'direccion' => 'Calle 50 # 30-10, Cali'],
        ];

        foreach ($centros as $centro) {
            Trainingcenter::updateOrCreate(
                ['nombre' => $centro['nombre']],
                ['direccion' => $centro['direccion']]
            );
        }
    }
}
