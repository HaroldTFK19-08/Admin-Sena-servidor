<?php

use App\Modules\Asignaciones\Controllers\Api\AssignmentApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('asignaciones', AssignmentApiController::class)
    ->parameters(['asignaciones' => 'asignacion'])
    ->names('api.asignaciones');
