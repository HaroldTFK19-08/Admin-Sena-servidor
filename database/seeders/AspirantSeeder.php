<?php

namespace Database\Seeders;

use App\Modules\Usuarios\Models\Aspirant;
use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Seeder;

class AspirantSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(UserSeeder::class);

        $usuarios = User::where('rol', 'Aspirante')->orderBy('id')->get();
        $eps = ['SURA', 'Nueva EPS', 'Salud Total'];

        foreach ($usuarios as $index => $usuario) {
            Aspirant::updateOrCreate(
                ['user_id' => $usuario->id],
                [
                    'eps' => $eps[$index % count($eps)],
                    'certificado_eps' => null,
                    'certificado_bachiller' => null,
                    'certificado_icfes' => null,
                ]
            );
        }
    }
}
