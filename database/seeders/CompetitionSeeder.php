<?php

namespace Database\Seeders;

use App\Modules\Competencias\Models\Competition;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Seeder;

class CompetitionSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(TeacherSeeder::class);

        $teachers = Teacher::orderBy('id')->get();
        $competencias = [
            ['codigo' => 'COMP-001', 'nombre' => 'Desarrollar componentes web', 'inicio' => '-6 months', 'fin' => '+6 months'],
            ['codigo' => 'COMP-002', 'nombre' => 'Aplicar identidad visual', 'inicio' => '-4 months', 'fin' => '+8 months'],
            ['codigo' => 'COMP-003', 'nombre' => 'Gestionar procesos administrativos', 'inicio' => '-3 months', 'fin' => '+9 months'],
        ];

        foreach ($competencias as $index => $competencia) {
            $inicio = now()->modify($competencia['inicio']);
            Competition::updateOrCreate(
                ['codigo' => $competencia['codigo']],
                [
                    'teacher_id' => $teachers[$index % $teachers->count()]->id,
                    'nombre' => $competencia['nombre'],
                    'descripcion' => 'Competencia de formación técnica.',
                    'fecha_inicio' => $inicio->toDateString(),
                    'fecha_fin' => $inicio->copy()->modify($competencia['fin'])->toDateString(),
                ]
            );
        }
    }
}
