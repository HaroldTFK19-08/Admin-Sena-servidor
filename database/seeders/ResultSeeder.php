<?php

namespace Database\Seeders;

use App\Modules\Competencias\Models\Competition;
use App\Modules\Resultados\Models\Result;
use Illuminate\Database\Seeder;

class ResultSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(CompetitionSeeder::class);

        $competencias = Competition::orderBy('id')->get();
        $resultados = [
            ['codigo' => 'RA-001', 'nombre' => 'Construir una interfaz web'],
            ['codigo' => 'RA-002', 'nombre' => 'Validar una interfaz web'],
            ['codigo' => 'RA-003', 'nombre' => 'Publicar una solución digital'],
        ];

        foreach ($competencias as $index => $competencia) {
            foreach ($resultados as $resultado) {
                $codigo = $resultado['codigo'].'-'.($index + 1);
                Result::updateOrCreate(
                    ['competition_id' => $competencia->id, 'codigo' => $codigo],
                    [
                        'nombre' => $resultado['nombre'],
                        'descripcion' => 'Resultado de aprendizaje verificable.',
                    ]
                );
            }
        }
    }
}
