<?php

namespace Database\Seeders;

use App\Modules\Areas\Models\Area;
use App\Modules\Programas\Models\Program;
use Illuminate\Database\Seeder;

class ProgramSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AreaSeeder::class);

        $programas = [
            ['area' => 'Tecnología', 'nombre' => 'Programación web', 'codigo' => 'PROG-001', 'nivel' => 'Tecnologo'],
            ['area' => 'Diseño', 'nombre' => 'Diseño gráfico digital', 'codigo' => 'PROG-002', 'nivel' => 'Tecnico'],
            ['area' => 'Administración', 'nombre' => 'Gestión administrativa', 'codigo' => 'PROG-003', 'nivel' => 'Tecnico'],
            ['area' => 'Deporte', 'nombre' => 'Entrenamiento deportivo', 'codigo' => 'PROG-004', 'nivel' => 'Tecnico'],
        ];

        foreach ($programas as $programa) {
            $area = Area::where('nombre', $programa['area'])->firstOrFail();
            Program::updateOrCreate(
                ['codigo_programa' => $programa['codigo']],
                [
                    'area_id' => $area->id,
                    'nombre' => $programa['nombre'],
                    'nivel_programa' => $programa['nivel'],
                ]
            );
        }
    }
}
