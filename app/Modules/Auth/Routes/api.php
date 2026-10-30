<?php

use App\Modules\Auth\Controllers\Api\AuthApiController;
use Illuminate\Support\Facades\Route;

/*
| Rutas públicas: cualquier persona puede registrarse (Aprendiz, Instructor
| o Aspirante) e iniciar sesión sin token. El rol Admin no se puede crear
| desde el registro (RegisterRequest solo acepta aprendiz, instructor y
| aspirante).
*/
Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthApiController::class, 'login'])->name('api.auth.login');
    Route::post('/register', [AuthApiController::class, 'register'])->name('api.auth.register');
    Route::get('/me', [AuthApiController::class, 'me'])->name('api.auth.me');
    Route::put('/profile', [AuthApiController::class, 'updateProfile'])->name('api.auth.profile.update');
    Route::post('/logout', [AuthApiController::class, 'logout'])->name('api.auth.logout');
});
