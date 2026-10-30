<?php

use App\Modules\Fichas\Controllers\Api\CourseGroupApiController;
use App\Http\Controllers\Api\TeacherAssignmentApiController;
use Illuminate\Support\Facades\Route;

Route::apiResource('fichas', CourseGroupApiController::class)
    ->parameters(['fichas' => 'ficha'])
    ->names('api.fichas');

foreach (['index' => 'get', 'store' => 'post', 'show' => 'get', 'update' => 'match', 'destroy' => 'delete'] as $action => $method) {
    $uri = $action === 'index' || $action === 'store'
        ? 'fichas-instructores'
        : 'fichas-instructores/{assignmentId}';
    $route = $method === 'match'
        ? Route::match(['put', 'patch'], $uri, [TeacherAssignmentApiController::class, $action])
        : Route::$method($uri, [TeacherAssignmentApiController::class, $action]);
    if ($action === 'show' || $action === 'update' || $action === 'destroy') {
        $route->whereNumber('assignmentId');
    }
    $route->defaults('assignmentType', 'fichas-instructores')
        ->name("api.fichas-instructores.{$action}");
}
