<?php

use App\Modules\Centros\Controllers\Api\TrainingCenterApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('centros', TrainingCenterApiController::class)
    ->parameters(['centros' => 'centro'])
    ->names('api.centros');
