<?php

use App\Modules\Areas\Controllers\Api\AreaApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('areas', AreaApiController::class)
    ->parameters(['areas' => 'area'])
    ->names('api.areas');
