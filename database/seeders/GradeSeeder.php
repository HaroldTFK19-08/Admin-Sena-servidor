<?php

namespace Database\Seeders;

use App\Modules\Resultados\Models\Grade;
use App\Modules\Resultados\Models\Result;
use App\Modules\Usuarios\Models\Apprentice;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Seeder;

class GradeSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([ResultSeeder::class, ApprenticeSeeder::class, TeacherSeeder::class]);

        $resultados = Result::orderBy('id')->get();
        $aprendices = Apprentice::orderBy('id')->get();
        $teachers = Teacher::orderBy('id')->get();
        $estados = ['Aprobado', 'Pendiente', 'Aprobado', 'Reprobado'];

        foreach ($resultados->take(6) as $resultado) {
            foreach ($aprendices->take(3) as $index => $aprendiz) {
                Grade::updateOrCreate(
                    ['result_id' => $resultado->id, 'apprentice_id' => $aprendiz->id],
                    [
                        'teacher_id' => $teachers->first()?->id,
                        'estado_resultado' => $estados[($resultado->id + $index) % count($estados)],
                        'comentarios' => 'Calificación registrada por el instructor.',
                    ]
                );
            }
        }
    }
}
