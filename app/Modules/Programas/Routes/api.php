<?php

use App\Modules\Programas\Controllers\Api\ProgramApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('programas', ProgramApiController::class)
    ->parameters(['programas' => 'programa'])
    ->names('api.programas');
