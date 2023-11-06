<?php

use App\Http\Controllers\Final_examController;
use App\Http\Controllers\RoleController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\TeamController;
use App\Http\Controllers\TaskController;

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
        Route::post("/refresh", UsersController::class ."@refresh");
        Route::post("/logout", UsersController::class ."@logout");
        Route::post("/logout-from-all", UsersController::class ."@logoutFromAll");
    });
});

Route::group([
    'prefix' => 'teams',
    'middleware' => ['auth'],
], function () {
    Route::get("/", TeamController::class . "@index");
    Route::post("/", TeamController::class . "@store");
    Route::get("/{id}", TeamController::class . "@show");
    Route::put("/{id}", TeamController::class . "@update");
    Route::delete("/{id}", TeamController::class . "@destroy");
});

Route::group([
    'prefix' => 'tasks',
    'middleware' => ['auth'],
], function (){
    Route::get("/", TaskController::class ."@index");
    Route::post("/{teamId}", TaskController::class ."@store");
    Route::get("/{teamId}/{taskId}", TaskController::class ."@show");
    Route::put("/{teamId}/{taskId}", TaskController::class ."@update");
    Route::delete("/{teamId}/{taskId}", TaskController::class ."@destroy");
    Route::post("/{teamId}/{taskId}/assign-user", TaskController::class ."@assignUser");
    Route::post("/{teamId}/{taskId}/unassign-user", TaskController::class ."@unassignUser");
});

Route::group([
    'prefix' => 'roles',
], function(){
    Route::get("/", RoleController::class ."@index");
    Route::post("/", RoleController::class ."@store");
    //ask how to manage deletion and creation of roles
});

Route::resource('final_exam', Final_examController::class);
