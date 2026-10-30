<?php

namespace Database\Seeders;

use App\Modules\Areas\Models\Area;
use App\Modules\Centros\Models\Trainingcenter;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(TrainingCenterSeeder::class);

        $areas = ['Tecnología', 'Administración', 'Diseño', 'Deporte'];
        $centros = Trainingcenter::query()->get();

        foreach ($areas as $index => $nombre) {
            Area::updateOrCreate(
                ['trainingcenter_id' => $centros[$index % $centros->count()]->id, 'nombre' => $nombre],
                []
            );
        }
    }
}
