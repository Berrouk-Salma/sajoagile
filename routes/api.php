<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TeamController;
use App\Http\Controllers\Api\ProjectController;
use App\Http\Controllers\Api\SprintController;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\ListaController;

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

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    // User routes
    Route::get('/user', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);

    // Team routes
    Route::apiResource('teams', TeamController::class);
    Route::get('/teams/{id}/members', [TeamController::class, 'getMembers']);
    Route::post('/teams/{id}/members', [TeamController::class, 'addMember']);
    Route::delete('/teams/{id}/members/{userId}', [TeamController::class, 'removeMember']);
    Route::get('/users/{userId}/teams', [TeamController::class, 'getTeamsByUser']);
    Route::get('/projects/{projectId}/teams', [TeamController::class, 'getTeamsByProject']);

    // Project routes
    Route::apiResource('projects', ProjectController::class);
    Route::get('/projects/{id}/members', [ProjectController::class, 'getMembers']);
    Route::post('/projects/{id}/members', [ProjectController::class, 'addMember']);
    Route::delete('/projects/{id}/members/{userId}', [ProjectController::class, 'removeMember']);
    Route::get('/projects/{id}/teams', [ProjectController::class, 'getTeams']);
    Route::post('/projects/{id}/teams', [ProjectController::class, 'addTeam']);
    Route::delete('/projects/{id}/teams/{teamId}', [ProjectController::class, 'removeTeam']);
    Route::get('/users/{userId}/projects', [ProjectController::class, 'getProjectsByUser']);
    Route::get('/teams/{teamId}/projects', [ProjectController::class, 'getProjectsByTeam']);
    Route::get('/projects/{id}/sprints', [ProjectController::class, 'getSprints']);
    Route::get('/projects/{id}/sprints/current', [ProjectController::class, 'getCurrentSprint']);

    // Sprint routes
    Route::apiResource('sprints', SprintController::class);
    Route::get('/sprints/{id}/tasks', [SprintController::class, 'getTasks']);
    Route::post('/sprints/{id}/tasks', [SprintController::class, 'addTask']);

    // Task routes
    Route::apiResource('tasks', TaskController::class);
    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus']);
    Route::put('/tasks/{id}/assign', [TaskController::class, 'assignToUser']);
    Route::get('/tasks/{id}/comments', [TaskController::class, 'getComments']);
    Route::post('/tasks/{id}/comments', [TaskController::class, 'addComment']);
    Route::get('/sprints/{sprintId}/tasks', [TaskController::class, 'getTasksBySprint']);
    Route::get('/users/{userId}/tasks', [TaskController::class, 'getTasksByUser']);
    Route::get('/tasks/status/{status}', [TaskController::class, 'getTasksByStatus']);
    Route::get('/tasks/priority/{priority}', [TaskController::class, 'getTasksByPriority']);

    // List routes
    Route::apiResource('lists', ListaController::class);
    Route::get('/projects/{projectId}/lists', [ListaController::class, 'getListsByProject']);
    Route::post('/projects/{projectId}/lists', [ListaController::class, 'addListToProject']);
    Route::delete('/projects/{projectId}/lists/{listId}', [ListaController::class, 'removeListFromProject']);

    // Admin-only routes
    Route::middleware('role:admin')->group(function () {
        // Add admin-specific routes here
        Route::get('/users', [AuthController::class, 'getAllUsers']);
    });
});
