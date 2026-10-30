<?php

namespace Database\Seeders;

use App\Modules\Asignaciones\Models\Assignment;
use App\Modules\Equipos\Models\Equipment;
use App\Modules\Usuarios\Models\Apprentice;
use Illuminate\Database\Seeder;

class AssignmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([EquipmentSeeder::class, ApprenticeSeeder::class]);

        $equipos = Equipment::orderBy('id')->get();
        $aprendices = Apprentice::orderBy('id')->get();

        foreach ($aprendices->take(3) as $index => $aprendiz) {
            $equipo = $equipos[$index % $equipos->count()];
            $fecha = now()->subDays(20 - ($index * 3));

            Assignment::updateOrCreate(
                ['equipment_id' => $equipo->id, 'apprentice_id' => $aprendiz->id],
                [
                    'fecha_asignacion' => $fecha->toDateTimeString(),
                    'fecha_devolucion' => $index === 2 ? $fecha->copy()->addDays(15)->toDateTimeString() : null,
                    'Estado' => $index === 2 ? 'Inactivo' : 'Activo',
                ]
            );
        }
    }
}
