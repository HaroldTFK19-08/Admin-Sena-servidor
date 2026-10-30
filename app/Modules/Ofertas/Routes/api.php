<?php

use App\Modules\Ofertas\Controllers\Api\OfferApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('ofertas', OfferApiController::class)
    ->parameters(['ofertas' => 'oferta'])
    ->names('api.ofertas');
