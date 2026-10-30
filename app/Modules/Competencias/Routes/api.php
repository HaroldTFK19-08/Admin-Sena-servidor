<?php

use App\Modules\Competencias\Controllers\Api\CompetitionApiController;
use App\Http\Controllers\Api\TeacherAssignmentApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('competencias', CompetitionApiController::class)
    ->parameters(['competencias' => 'competencia'])
    ->names('api.competencias');

foreach (['index' => 'get', 'store' => 'post', 'show' => 'get', 'update' => 'match', 'destroy' => 'delete'] as $action => $method) {
    $uri = $action === 'index' || $action === 'store'
        ? 'competencias-instructores'
        : 'competencias-instructores/{assignmentId}';
    $route = $method === 'match'
        ? Route::match(['put', 'patch'], $uri, [TeacherAssignmentApiController::class, $action])
        : Route::$method($uri, [TeacherAssignmentApiController::class, $action]);
    if ($action === 'show' || $action === 'update' || $action === 'destroy') {
        $route->whereNumber('assignmentId');
    }
    $route->defaults('assignmentType', 'competencias-instructores')
        ->name("api.competencias-instructores.{$action}");
}
