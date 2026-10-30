<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Rutas generales de la API. Se registran con el prefijo /v1 y el grupo
| de middleware "api" (sin autenticación, el proyecto es de práctica).
|
| Los recursos CRUD por dominio viven en la carpeta Routes de cada dominio.
|
*/

Route::get('/user', function (Request $request) {
    return response()->json([
        'success' => true,
        'message' => $request->user()
            ? 'Usuario autenticado.'
            : 'Ruta abierta: no se requiere autenticación.',
        'data' => $request->user(),
    ]);
})->name('api.user');
