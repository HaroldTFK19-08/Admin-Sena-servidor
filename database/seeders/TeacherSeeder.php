<?php

namespace Database\Seeders;

use App\Modules\Areas\Models\Area;
use App\Modules\Usuarios\Models\Teacher;
use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([UserSeeder::class, AreaSeeder::class]);

        $usuarios = User::where('rol', 'Instructor')->orderBy('id')->get();
        $areas = Area::orderBy('id')->get();
        $especialidades = ['Desarrollo web', 'Diseño digital', 'Gestión administrativa'];

        foreach ($usuarios as $index => $usuario) {
            Teacher::updateOrCreate(
                ['user_id' => $usuario->id],
                [
                    'area_id' => $areas[$index % $areas->count()]->id,
                    'especialidad' => $especialidades[$index % count($especialidades)],
                    'tipo_instructor' => $index === 0 ? 'Planta' : 'Contratista',
                    'fecha_inicio' => now()->subYears(2)->toDateString(),
                    'fecha_fin' => null,
                ]
            );
        }
    }
}
