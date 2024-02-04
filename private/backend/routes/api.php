<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\IncidentsController;
use App\Http\Controllers\TaskReponseController;
use App\Http\Controllers\IncidentTaskController;
use App\Http\Controllers\TaskResponseController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::group([
    'prefix' => 'users',
], function () {
    Route::post("/register", UsersController::class . "@register");
    Route::post("/login", UsersController::class . "@login");

    Route::group([
        'middleware' => ['auth'],
    ], function () {
        Route::get('/', UsersController::class . '@index');
        Route::post("/refresh", UsersController::class . "@refresh");
        Route::post("/logout", UsersController::class . "@logout");
        Route::post("/logout-from-all", UsersController::class . "@logoutFromAll");
    });
});

// Route::group([
//     'prefix' => 'teams',
//     'middleware' => ['auth'],
// ], function () {
//     Route::get("/", TeamController::class . "@index");
//     Route::post("/", TeamController::class . "@store");
//     Route::get("/{id}", TeamController::class . "@show");
//     Route::put("/{id}", TeamController::class . "@update");
//     Route::delete("/{id}", TeamController::class . "@destroy");
// });

Route::group([
    'prefix' => 'tasks',
    'middleware' => ['auth'],
], function () {
    Route::get("/", TaskController::class . "@index");
    Route::post("/{teamId}", TaskController::class . "@store");
    Route::get("/{teamId}/{taskId}", TaskController::class . "@show");
    Route::put("/{teamId}/{taskId}", TaskController::class . "@update");
    Route::delete("/{teamId}/{taskId}", TaskController::class . "@destroy");
    Route::post("/{teamId}/{taskId}/assign-user", TaskController::class . "@assignUser");
    Route::post("/{teamId}/{taskId}/unassign-user", TaskController::class . "@unassignUser");
});

Route::group([
    'prefix' => 'incidents',
    'middleware' => ['auth'],
], function () {
    // Route::get("/", IncidentsController::class ."@index");
    // Route::get("/{id}", IncidentsController::class ."@show");
    // Route::put("/{id}", IncidentsController::class ."@update");
    // Route::delete("/{id}", IncidentsController::class ."@destroy");
    Route::get('type/{type}', IncidentsController::class . "@getIncidentsByType");
    Route::get("/{id}/images", IncidentsController::class . "@getImages");
});

Route::resource('thesis', ThesisController::class);

Route::group([
    'prefix' => '/',
    'middleware' => ['auth'],
], function () {
    Route::resource('teams', TeamController::class);
    Route::resource('incidents', IncidentsController::class);
    Route::resource('roles', RoleController::class)->only(['index', 'store']);
    Route::resource('tags', TagController::class);

    Route::get('task/{taskId}/responses', TaskResponseController::class . '@index');
    Route::post('task/{taskId}/responses', TaskResponseController::class . '@store');
    Route::get('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@show');
    Route::put('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@update');
    Route::delete('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@destroy');
});

