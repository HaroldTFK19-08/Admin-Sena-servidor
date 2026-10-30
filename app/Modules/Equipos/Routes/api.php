<?php

use App\Modules\Equipos\Controllers\Api\EquipmentApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('equipos', EquipmentApiController::class)
    ->parameters(['equipos' => 'equipo'])
    ->names('api.equipos');
