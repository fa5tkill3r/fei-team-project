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
    /**
     *  Register new user
     *
     * @endpoint /api/users/register
     * @method POST
     */
    Route::post("/register", UsersController::class . "@register");
    /**
     * Login user
     *
     * @endpoint /api/users/login
     * @method POST
     */
    Route::post("/login", UsersController::class . "@login");
    /**
     * Restore user
     *
     * @endpoint /api/users/restore
     * @method POST
     */
    Route::post("/restore", UsersController::class . "@restore");

    Route::group([
        'middleware' => ['auth'],
    ], function () {
        /**
         * Get all users
         *
         * @endpoint /api/users
         * @method GET
         */
        Route::get('/', UsersController::class . '@index');
        /**
         * Refresh token
         *
         * @endpoint /api/users/refresh
         * @method POST
         */
        Route::post("/refresh", UsersController::class . "@refresh");
        /**
         * Logout user
         *
         * @endpoint /api/users/logout
         * @method POST
         */
        Route::post("/logout", UsersController::class . "@logout");
        /**
         * Logout user from all devices
         *
         * @endpoint /api/users/logout-from-all
         * @method POST
         */
        Route::post("/logout-from-all", UsersController::class . "@logoutFromAll");
        /**
         * Update user avatar
         *
         * @endpoint /api/users/avatar
         * @method PUT
         */
        Route::put("/avatar", UsersController::class . "@updateAvatar");
        /**
         * Delete user avatar
         *
         * @endpoint /api/users/avatar
         * @method DELETE
         */
        Route::delete("/avatar", UsersController::class . "@deleteAvatar");
    });
});


Route::group([
    'prefix' => 'tasks',
    'middleware' => ['auth'],
], function () {
    /**
     * Get all tasks
     *
     * @endpoint /api/tasks
     * @method GET
     */
    Route::get("/", TaskController::class . "@index");
    /**
     * Create new task
     *
     * @endpoint /api/tasks/{teamId}
     * @method POST
     */
    Route::post("/{teamId}", TaskController::class . "@store");
    /**
     * Get task by id
     *
     * @endpoint /api/tasks/{teamId}/{taskId}
     * @method GET
     */
    Route::get("/{teamId}/{taskId}", TaskController::class . "@show");
    /**
     * Update task by id
     *
     * @endpoint /api/tasks/{teamId}/{taskId}
     * @method PUT
     */
    Route::put("/{teamId}/{taskId}", TaskController::class . "@update");
    /**
     * Delete task by id
     *
     * @endpoint /api/tasks/{teamId}/{taskId}
     * @method DELETE
     */
    Route::delete("/{teamId}/{taskId}", TaskController::class . "@destroy");
    /**
     * Assign user to task
     *
     * @endpoint /api/tasks/{teamId}/{taskId}/assign-user
     * @method POST
     */
    Route::post("/{teamId}/{taskId}/assign-user", TaskController::class . "@assignUser");
    /**
     * Unassign user from task
     *
     * @endpoint /api/tasks/{teamId}/{taskId}/unassign-user
     * @method POST
     */
    Route::post("/{teamId}/{taskId}/unassign-user", TaskController::class . "@unassignUser");
});

Route::group([
    'prefix' => 'incidents',
    'middleware' => ['auth'],
], function () {
    /**
     * Get all incidents
     *
     * @endpoint /api/incidents
     * @method GET
     */
    Route::get("/", IncidentsController::class . "@index");
    /**
     * Get incident by id
     *
     * @endpoint /api/incidents/{id}
     * @method GET
     */
    Route::get('/{id}', IncidentsController::class . '@show');
    /**
     * Update incident by id
     *
     * @endpoint /api/incidents/{id}
     * @method PUT
     */
    Route::put('/{id}', IncidentsController::class . '@update');
    /**
     * Delete incident by id
     *
     * @endpoint /api/incidents/{id}
     * @method DELETE
     */
    Route::delete('/{id}', IncidentsController::class . '@destroy');
    /**
     * Get all incidents by type
     *
     * @endpoint /api/incidents/type/{type}
     * @method GET
     */
    Route::get('/type/{type}', IncidentsController::class . "@getIncidentsByType");
    /**
     * Get all incident images
     *
     * @endpoint /api/incidents/{id}/images
     * @method GET
     */
    Route::get("/{id}/images", IncidentsController::class . "@getImages");
});

/**
 * Create new incident
 *
 * @endpoint /api/incidents
 * @method POST
 */
Route::post('incidents', IncidentsController::class . "@store");

/**
 * Thesis routes
 *
 * @endpoint /api/thesis
 * @method GET
 *
 * @endpoint /api/thesis
 * @method POST
 *
 * @endpoint /api/thesis/{id}
 * @method GET
 *
 * @endpoint /api/thesis/{id}
 * @method PUT
 *
 * @endpoint /api/thesis/{id}
 * @method DELETE
 *
 */
Route::resource('thesis', ThesisController::class);

Route::group([
    'prefix' => '/',
    'middleware' => ['auth'],
], function () {
    /**
     * Team routes
     *
     * @endpoint /api/teams
     * @method GET
     *
     * @endpoint /api/teams
     * @method POST
     *
     * @endpoint /api/teams/{id}
     * @method GET
     *
     * @endpoint /api/teams/{id}
     * @method PUT
     *
     * @endpoint /api/teams/{id}
     * @method DELETE
     *
     */
    Route::resource('teams', TeamController::class);
    /**
     * Remove user from team
     *
     * @endpoint /api/teams/{id}/user
     * @method DELETE
     */
    Route::delete('teams/{id}/user', TeamController::class . '@removeUser');
    /**
     * Add user to team
     *
     * @endpoint /api/teams/{id}/user
     * @method POST
     */
    Route::post('teams/{id}/user', TeamController::class . '@addUser');
    /**
     * Update user role in team
     *
     * @endpoint /api/teams/{id}/user
     * @method PUT
     */
    Route::put('teams/{id}/user', TeamController::class . '@updateUserRole');
    /**
     * Roles routes
     *
     * @endpoint /api/roles
     * @method GET
     *
     * @endpoint /api/roles
     * @method POST
     *
     * @endpoint /api/roles/{id}
     * @method GET
     *
     * @endpoint /api/roles/{id}
     * @method PUT
     *
     * @endpoint /api/roles/{id}
     * @method DELETE
     */
    Route::resource('roles', RoleController::class)->only(['index', 'store', 'show', 'update', 'destroy']);

    /**
     * Tags routes
     *
     * @endpoint /api/tags
     * @method GET
     *
     * @endpoint /api/tags
     * @method POST
     *
     * @endpoint /api/tags/{id}
     * @method GET
     *
     * @endpoint /api/tags/{id}
     * @method PUT
     *
     * @endpoint /api/tags/{id}
     * @method DELETE
     */
    Route::resource('tags', TagController::class);

    /**
     * Get task responses
     *
     * @endpoint /api/task/{taskId}/responses
     * @method GET
     */
    Route::get('task/{taskId}/responses', TaskResponseController::class . '@index');
    /**
     * Create new task response
     *
     * @endpoint /api/task/{taskId}/responses
     * @method POST
     */
    Route::post('task/{taskId}/responses', TaskResponseController::class . '@store');
    /**
     * Get task response by id
     *
     * @endpoint /api/task/{taskId}/responses/{responseId}
     * @method GET
     */
    Route::get('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@show');
    /**
     * Update task response by id
     *
     * @endpoint /api/task/{taskId}/responses/{responseId}
     * @method PUT
     */
    Route::put('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@update');
    /**
     * Delete task response by id
     *
     * @endpoint /api/task/{taskId}/responses/{responseId}
     * @method DELETE
     */
    Route::delete('task/{taskId}/responses/{responseId}', TaskResponseController::class . '@destroy');

    /**
     * Task comments routes
     *
     * @endpoint /api/task/{taskId}/comments
     * @method GET
     *
     * @endpoint /api/task/{taskId}/comments
     * @method POST
     *
     * @endpoint /api/task/{taskId}/comments/{commentId}
     * @method GET
     *
     * @endpoint /api/task/{taskId}/comments/{commentId}
     * @method PUT
     *
     * @endpoint /api/task/{taskId}/comments/{commentId}
     * @method DELETE
     *
     */
    Route::apiResource('task/{taskId}/comments', TaskCommentController::class)->only(['index', 'store', 'show', 'update', 'destroy']);
});

Route::group([
    'prefix' => 'incident-info',
    'middleware' => ['auth'],
], function () {
    /**
     * Get all additional info incidents
     *
     * @endpoint /api/incident-info/{id}
     * @method GET
     */
    Route::get("/{id}", AdditionalInfoIncidentController::class . "@show");
    /**
     * Update additional info incident
     *
     * @endpoint /api/incident-info/{id}
     * @method PUT
     */
    Route::put("/{id}", AdditionalInfoIncidentController::class . "@update");
    /**
     * Delete additional info incident
     *
     * @endpoint /api/incident-info/{id}
     * @method DELETE
     */
    Route::delete("/{id}", AdditionalInfoIncidentController::class . "@destroy");
    /**
     * Create new additional info incident
     *
     * @endpoint /api/incident-info
     * @method POST
     */
    Route::post("/", AdditionalInfoIncidentController::class . "@store");
    /**
     * Get all additional info incidents
     *
     * @endpoint /api/incident-info
     * @method GET
     */
    Route::get("/enum-values", AdditionalInfoIncidentController::class . "@getEnumValues");
});


Route::group([
    'prefix' => 'incident-categories',
    'middleware' => ['auth'],
], function () {
    /**
     * Get all incident categories
     *
     * @endpoint /api/incident-categories
     * @method GET
     */
    Route::get("/", IncidentCategoryController::class . "@index");
    /**
     * Create new incident category
     *
     * @endpoint /api/incident-categories
     * @method POST
     */
    Route::post("/", IncidentCategoryController::class . "@store");
    /**
     * Update incident category
     *
     * @endpoint /api/incident-categories/{id}
     * @method PUT
     */
    Route::put("/{id}", IncidentCategoryController::class . "@update");
    /**
     * Delete incident category
     *
     * @endpoint /api/incident-categories/{id}
     * @method DELETE
     */
    Route::delete("/{id}", IncidentCategoryController::class . "@destroy");
});


Route::group([
    'prefix' => 'incident-types',
    'middleware' => ['auth'],
], function () {
    /**
     * Get all incident types
     *
     * @endpoint /api/incident-types
     * @method GET
     */
    Route::get("/", IncidentTypeController::class . "@index");
    /**
     * Create new incident type
     *
     * @endpoint /api/incident-types
     * @method POST
     */
    Route::post("/", IncidentTypeController::class . "@store");
    /**
     * Update incident type
     *
     * @endpoint /api/incident-types/{id}
     * @method PUT
     */
    Route::put("/{id}", IncidentTypeController::class . "@update");
    /**
     * Delete incident type
     *
     * @endpoint /api/incident-types/{id}
     * @method DELETE
     */
    Route::delete("/{id}", IncidentTypeController::class . "@destroy");
});

Route::group([
    'prefix' => 'incident-solutions',
    'middleware' => ['auth'],
], function () {
    /**
     * Get all incident solutions
     *
     * @endpoint /api/incident-solutions/{incidentId}
     * @method GET
     */
    Route::get("/{incidentId}", IncidentSolutionsController::class . "@index");
    /**
     * Create new incident solution
     *
     * @endpoint /api/incident-solutions/{incidentId}
     * @method POST
     */
    Route::post("/{incidentId}", IncidentSolutionsController::class . "@store");
    /**
     * Get incident solution by id
     *
     * @endpoint /api/incident-solutions/{incidentId}/{solutionId}
     * @method GET
     */
    Route::get("/{incidentId}/{solutionId}", IncidentSolutionsController::class . "@show");
    /**
     * Update incident solution by id
     *
     * @endpoint /api/incident-solutions/{incidentId}/{solutionId}
     * @method PUT
     */
    Route::put("/{incidentId}/{solutionId}", IncidentSolutionsController::class . "@update");
    /**
     * Delete incident solution by id
     *
     * @endpoint /api/incident-solutions/{incidentId}/{solutionId}
     * @method DELETE
     */
    Route::delete("/{incidentId}/{solutionId}", IncidentSolutionsController::class . "@destroy");
});

Route::group([
    'prefix' => 'incident-chronologically',
    'middleware' => ['auth'],
], function () {
    /**
     * Get all incident chronologically
     *
     * @endpoint /api/incident-chronologically/{incidentId}
     * @method GET
     */
    Route::get("/{incidentId}", IncidentChronologicallyController::class . "@index");
    /**
     * Create new incident chronologically
     *
     * @endpoint /api/incident-chronologically/{incidentId}
     * @method POST
     */
    Route::post("/{incidentId}", IncidentChronologicallyController::class . "@store");
    /**
     * Get incident chronologically by id
     *
     * @endpoint /api/incident-chronologically/{incidentId}/{incidentChronologicallyId}
     * @method GET
     */
    Route::get("/{incidentId}/{incidentChronologicallyId}", IncidentChronologicallyController::class . "@show");
    /**
     * Update incident chronologically by id
     *
     * @endpoint /api/incident-chronologically/{incidentId}/{incidentChronologicallyId}
     * @method PUT
     */
    Route::put("/{incidentId}/{incidentChronologicallyId}", IncidentChronologicallyController::class . "@update");
    /**
     * Delete incident chronologically by id
     *
     * @endpoint /api/incident-chronologically/{incidentId}/{incidentChronologicallyId}
     */
    Route::delete("/{incidentId}/{incidentChronologicallyId}", IncidentChronologicallyController::class . "@destroy");
});

/**F
 * Get pdf report from incident
 *
 * @endpoint /api/generate-pdf/{incidentId}
 */
Route::get('generate-pdf/{incidentId}', IncidentReportPDFGenerator::class . '@generatePDF');
