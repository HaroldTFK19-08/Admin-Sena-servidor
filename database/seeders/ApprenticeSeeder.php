<?php

namespace Database\Seeders;

use App\Modules\Fichas\Models\Course_group;
use App\Modules\Usuarios\Models\Apprentice;
use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Seeder;

class ApprenticeSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([UserSeeder::class, CourseGroupSeeder::class]);

        $usuarios = User::where('rol', 'Aprendiz')->orderBy('id')->get();
        $fichas = Course_group::orderBy('id')->get();
        $estados = ['Activo', 'Activo', 'Activo', 'Certificado'];

        foreach ($usuarios as $index => $usuario) {
            Apprentice::updateOrCreate(
                ['user_id' => $usuario->id],
                [
                    'course_group_id' => $fichas[$index % $fichas->count()]->id,
                    'estado' => $estados[$index % count($estados)],
                    'fecha_inicio' => now()->subMonths(4)->toDateString(),
                    'fecha_fin' => now()->addMonths(8)->toDateString(),
                ]
            );
        }
    }
}
