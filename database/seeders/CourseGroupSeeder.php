<?php

namespace Database\Seeders;

use App\Modules\Fichas\Models\Course_group;
use App\Modules\Programas\Models\Program;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Seeder;

class CourseGroupSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([ProgramSeeder::class, TeacherSeeder::class]);

        $programas = Program::orderBy('id')->get();
        $teachers = Teacher::orderBy('id')->get();
        $grupos = [
            ['programa' => 'Programación web', 'codigo' => 'FIC-2026-001'],
            ['programa' => 'Diseño gráfico digital', 'codigo' => 'FIC-2026-002'],
            ['programa' => 'Gestión administrativa', 'codigo' => 'FIC-2026-003'],
        ];

        foreach ($grupos as $index => $grupo) {
            $programa = $programas->firstWhere('nombre', $grupo['programa']);
            $teacher = $teachers[$index % $teachers->count()];

            Course_group::updateOrCreate(
                ['codigo' => $grupo['codigo']],
                [
                    'program_id' => $programa->id,
                    'teacher_id' => $teacher->id,
                    'capacidad_aprendices' => 30,
                ]
            );
        }
    }
}
