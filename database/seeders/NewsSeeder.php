<?php

namespace Database\Seeders;

use App\Modules\Centros\Models\Trainingcenter;
use App\Modules\Noticias\Models\News;
use App\Modules\Usuarios\Models\Admin;
use Illuminate\Database\Seeder;

class NewsSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([TrainingCenterSeeder::class, AdminSeeder::class]);

        $centro = Trainingcenter::query()->firstOrFail();
        $admin = Admin::firstOrFail();
        $noticias = [
            ['titulo' => 'Convocatoria de formación técnica', 'categoria' => 'Educacion', 'estado' => 'Publicado', 'publicada' => true],
            ['titulo' => 'Calendario de actividades del centro', 'categoria' => 'Tecnologia', 'estado' => 'Publicado', 'publicada' => true],
            ['titulo' => 'Jornada de actualización docente', 'categoria' => 'Educacion', 'estado' => 'Pendiente', 'publicada' => false],
        ];

        foreach ($noticias as $noticia) {
            News::updateOrCreate(
                ['trainingcenter_id' => $centro->id, 'titulo' => $noticia['titulo']],
                [
                    'admin_id' => $admin->id,
                    'descripcion' => 'Información institucional para la comunidad SENA.',
                    'imagen' => null,
                    'estado' => $noticia['estado'],
                    'categoria' => $noticia['categoria'],
                    'publicada' => $noticia['publicada'],
                ]
            );
        }
    }
}
