<?php

namespace Database\Seeders;

use App\Modules\Ambientes\Models\Environment;
use App\Modules\Equipos\Models\Equipment;
use Illuminate\Database\Seeder;

class EquipmentSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(EnvironmentSeeder::class);

        $laboratorio = Environment::where('nombre', 'Laboratorio de software')->firstOrFail();
        $equipos = [
            ['name' => 'Kit de desarrollo web', 'brand' => 'SENA', 'equipment_type' => 'Equipo de cómputo'],
            ['name' => 'Router de escritorio', 'brand' => 'AULA', 'equipment_type' => 'Equipo de redes'],
            ['name' => 'Multímetro digital', 'brand' => 'Genérico', 'equipment_type' => 'Instrumento'],
            ['name' => 'Simulador industrial', 'brand' => 'SENA', 'equipment_type' => 'Simulador'],
        ];

        foreach ($equipos as $equipo) {
            Equipment::updateOrCreate(
                ['environment_id' => $laboratorio->id, 'name' => $equipo['name']],
                ['brand' => $equipo['brand'], 'equipment_type' => $equipo['equipment_type']]
            );
        }
    }
}
