<?php

use App\Modules\Usuarios\Controllers\Api\ApprenticeApiController;
use App\Modules\Usuarios\Controllers\Api\RoleProfileApiController;
use App\Modules\Usuarios\Controllers\Api\TeacherApiController;
use App\Modules\Usuarios\Controllers\Api\UserApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('aprendices', ApprenticeApiController::class)
    ->parameters(['aprendices' => 'aprendiz'])
    ->names('api.aprendices');

Route::apiResource('instructores', TeacherApiController::class)
    ->parameters(['instructores' => 'instructor'])
    ->names('api.instructores');
Route::middleware('admin.api')->group(function () {
    Route::apiResource('usuarios', UserApiController::class)
        ->parameters(['usuarios' => 'usuario'])
        ->names('api.usuarios');
    foreach (['admins', 'aspirantes'] as $profileType) {
        Route::get($profileType, [RoleProfileApiController::class, 'index'])
            ->defaults('profileType', $profileType)
            ->name("api.{$profileType}.index");
        Route::post($profileType, [RoleProfileApiController::class, 'store'])
            ->defaults('profileType', $profileType)
            ->name("api.{$profileType}.store");
        Route::get("{$profileType}/{profileId}", [RoleProfileApiController::class, 'show'])
            ->whereNumber('profileId')
            ->defaults('profileType', $profileType)
            ->name("api.{$profileType}.show");
        Route::match(['put', 'patch'], "{$profileType}/{profileId}", [RoleProfileApiController::class, 'update'])
            ->whereNumber('profileId')
            ->defaults('profileType', $profileType)
            ->name("api.{$profileType}.update");
        Route::delete("{$profileType}/{profileId}", [RoleProfileApiController::class, 'destroy'])
            ->whereNumber('profileId')
            ->defaults('profileType', $profileType)
            ->name("api.{$profileType}.destroy");
    }
});
