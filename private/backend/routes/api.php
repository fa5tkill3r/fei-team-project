<?php

use App\Http\Controllers\AdditionalInfoIncidentController;
use App\Http\Controllers\IncidentCategoryController;
use App\Http\Controllers\IncidentChronologicallyController;
use App\Http\Controllers\IncidentReportPDFGenerator;
use App\Http\Controllers\IncidentSolutionsController;
use App\Http\Controllers\IncidentTypeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TagController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\TaskController;
use App\Http\Controllers\TeamController;

use App\Http\Controllers\UsersController;
use App\Http\Controllers\ThesisController;
use App\Http\Controllers\IncidentsController;
use App\Http\Controllers\TaskCommentController;
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
    Route::post("/restore", UsersController::class . "@restore");

    Route::group([
        'middleware' => ['auth'],
    ], function () {
        Route::get('/', UsersController::class . '@index');
        Route::post("/refresh", UsersController::class . "@refresh");
        Route::post("/logout", UsersController::class . "@logout");
        Route::post("/logout-from-all", UsersController::class . "@logoutFromAll");

        Route::put("/avatar", UsersController::class . "@updateAvatar");
        Route::delete("/avatar", UsersController::class . "@deleteAvatar");
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
    Route::get("/", IncidentsController::class . "@index");
    Route::get('/{id}', IncidentsController::class . '@show');
    Route::put('/{id}', IncidentsController::class . '@update');
    Route::delete('/{id}', IncidentsController::class . '@destroy');

    Route::get('/type/{type}', IncidentsController::class . "@getIncidentsByType");
    Route::get("/{id}/images", IncidentsController::class . "@getImages");
});

Route::post('incidents', IncidentsController::class . "@store");

Route::resource('thesis', ThesisController::class);

Route::group([
    'prefix' => '/',
    'middleware' => ['auth'],
], function () {
    Route::resource('teams', TeamController::class);
    Route::delete('teams/{id}/user', TeamController::class . '@removeUser');
    Route::post('teams/{id}/user', TeamController::class . '@addUser');
    Route::put('teams/{id}/user', TeamController::class . '@updateUserRole');
    Route::resource('roles', RoleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
    Route::resource('tags', TagController::class);

    Route::get('task/{taskId}/responses', TaskResponseController::class . '@index');
    Route::post('task/{taskId}/responses', TaskResponseController::class . '@store');
    Route::get('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@show');
    Route::put('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@update');
    Route::delete('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@destroy');

    Route::apiResource('task/{taskId}/comments', TaskCommentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});

Route::group([
    'prefix' => 'incident-info',
    'middleware' => ['auth'],
], function () {
    Route::get("/{id}", AdditionalInfoIncidentController::class . "@show");
    Route::put("/{id}", AdditionalInfoIncidentController::class . "@update");
    Route::delete("/{id}", AdditionalInfoIncidentController::class . "@destroy");
    Route::post("/", AdditionalInfoIncidentController::class . "@store");
    Route::get("/enum-values", AdditionalInfoIncidentController::class . "@getEnumValues");
});


Route::group([
    'prefix' => 'incident-categories',
    'middleware' => ['auth'],
], function () {
    Route::get("/", IncidentCategoryController::class . "@index");
    Route::post("/", IncidentCategoryController::class . "@store");
    Route::put("/{id}", IncidentCategoryController::class . "@update");
    Route::delete("/{id}", IncidentCategoryController::class . "@destroy");
});


Route::group([
    'prefix' => 'incident-types',
    'middleware' => ['auth'],
], function () {
    Route::get("/", IncidentTypeController::class . "@index");
    Route::post("/", IncidentTypeController::class . "@store");
    Route::put("/{id}", IncidentTypeController::class . "@update");
    Route::delete("/{id}", IncidentTypeController::class . "@destroy");
});

Route::group([
    'prefix' => 'incident-solutions',
    'middleware' => ['auth'],
], function () {
    Route::get("/{incidentId}", IncidentSolutionsController::class . "@index");
    Route::post("/{incidentId}", IncidentSolutionsController::class . "@store");
    Route::get("/{incidentId}/{solutionId}", IncidentSolutionsController::class . "@show");
    Route::put("/{incidentId}/{solutionId}", IncidentSolutionsController::class . "@update");
    Route::delete("/{incidentId}/{solutionId}", IncidentSolutionsController::class . "@destroy");
});

Route::group([
    'prefix' => 'incident-chronologically',
    'middleware' => ['auth'],
], function () {
    Route::get("/{incidentId}", IncidentChronologicallyController::class . "@index");
    Route::post("/{incidentId}", IncidentChronologicallyController::class . "@store");
    Route::get("/{incidentId}/{incidentChronologicallyId}", IncidentChronologicallyController::class . "@show");
    Route::put("/{incidentId}/{incidentChronologicallyId}", IncidentChronologicallyController::class . "@update");
    Route::delete("/{incidentId}/{incidentChronologicallyId}", IncidentChronologicallyController::class . "@destroy");
});

Route::get('generate-pdf/{incidentId}', IncidentReportPDFGenerator::class. '@generatePDF');
