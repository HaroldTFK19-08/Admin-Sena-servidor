<?php

namespace Database\Seeders;

use App\Modules\Usuarios\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $usuarios = [
            ['email' => 'admin@adminsena.com', 'nombre_1' => 'Carlos', 'apellido_1' => 'Pérez', 'rol' => 'Admin', 'identificacion' => '1000000001'],
            ['email' => 'instructor1@adminsena.com', 'nombre_1' => 'Laura', 'apellido_1' => 'Gómez', 'rol' => 'Instructor', 'identificacion' => '1000000002'],
            ['email' => 'instructor2@adminsena.com', 'nombre_1' => 'Andrés', 'apellido_1' => 'Ramírez', 'rol' => 'Instructor', 'identificacion' => '1000000003'],
            ['email' => 'aprendiz1@adminsena.com', 'nombre_1' => 'Mateo', 'apellido_1' => 'Ríos', 'rol' => 'Aprendiz', 'identificacion' => '1000000004'],
            ['email' => 'aprendiz2@adminsena.com', 'nombre_1' => 'Sara', 'apellido_1' => 'López', 'rol' => 'Aprendiz', 'identificacion' => '1000000005'],
            ['email' => 'aprendiz3@adminsena.com', 'nombre_1' => 'Juan', 'apellido_1' => 'Díaz', 'rol' => 'Aprendiz', 'identificacion' => '1000000006'],
            ['email' => 'aprendiz4@adminsena.com', 'nombre_1' => 'Valentina', 'apellido_1' => 'Cruz', 'rol' => 'Aprendiz', 'identificacion' => '1000000007'],
            ['email' => 'aspirante1@adminsena.com', 'nombre_1' => 'Ana', 'apellido_1' => 'Pérez', 'rol' => 'Aspirante', 'identificacion' => '1000000008'],
            ['email' => 'aspirante2@adminsena.com', 'nombre_1' => 'Daniel', 'apellido_1' => 'Rojas', 'rol' => 'Aspirante', 'identificacion' => '1000000009'],
            ['email' => 'aspirante3@adminsena.com', 'nombre_1' => 'Sofía', 'apellido_1' => 'Molina', 'rol' => 'Aspirante', 'identificacion' => '1000000010'],
        ];

        foreach ($usuarios as $usuario) {
            User::updateOrCreate(
                ['email' => $usuario['email']],
                [
                    'nombre_1' => $usuario['nombre_1'],
                    'apellido_1' => $usuario['apellido_1'],
                    'rol' => $usuario['rol'],
                    'tipo_identificacion' => 'CC',
                    'identificacion' => $usuario['identificacion'],
                    'password' => Hash::make('12345678'),
                ]
            );
        }
    }
}
