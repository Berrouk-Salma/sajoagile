<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Web\AuthController;
use App\Http\Controllers\Web\DashboardController;
use App\Http\Controllers\Web\TeamController;
use App\Http\Controllers\Web\ProjectController;
use App\Http\Controllers\Web\SprintController;
use App\Http\Controllers\Web\TaskController;
use App\Http\Controllers\Web\ListController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Public routes
Route::get('/', function () {
    return view('welcome');
});

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Protected routes
Route::middleware(['auth'])->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/profile', [AuthController::class, 'showProfile'])->name('profile');
    Route::put('/profile', [AuthController::class, 'updateProfile'])->name('profile.update');

    // Teams
    Route::get('/teams', [TeamController::class, 'index'])->name('teams.index');
    Route::get('/teams/create', [TeamController::class, 'create'])->name('teams.create');
    Route::post('/teams', [TeamController::class, 'store'])->name('teams.store');
    Route::get('/teams/{id}', [TeamController::class, 'show'])->name('teams.show');
    Route::get('/teams/{id}/edit', [TeamController::class, 'edit'])->name('teams.edit');
    Route::put('/teams/{id}', [TeamController::class, 'update'])->name('teams.update');
    Route::delete('/teams/{id}', [TeamController::class, 'destroy'])->name('teams.destroy');
    Route::get('/teams/{id}/members', [TeamController::class, 'showMembers'])->name('teams.members');
    Route::post('/teams/{id}/members', [TeamController::class, 'addMember'])->name('teams.members.add');
    Route::delete('/teams/{id}/members/{userId}', [TeamController::class, 'removeMember'])->name('teams.members.remove');

    // Projects
    Route::get('/projects', [ProjectController::class, 'index'])->name('projects.index');
    Route::get('/projects/create', [ProjectController::class, 'create'])->name('projects.create');
    Route::post('/projects', [ProjectController::class, 'store'])->name('projects.store');
    Route::get('/projects/{id}', [ProjectController::class, 'show'])->name('projects.show');
    Route::get('/projects/{id}/edit', [ProjectController::class, 'edit'])->name('projects.edit');
    Route::put('/projects/{id}', [ProjectController::class, 'update'])->name('projects.update');
    Route::delete('/projects/{id}', [ProjectController::class, 'destroy'])->name('projects.destroy');
    Route::get('/projects/{id}/members', [ProjectController::class, 'showMembers'])->name('projects.members');
    Route::post('/projects/{id}/members', [ProjectController::class, 'addMember'])->name('projects.members.add');
    Route::delete('/projects/{id}/members/{userId}', [ProjectController::class, 'removeMember'])->name('projects.members.remove');
    Route::get('/projects/{id}/teams', [ProjectController::class, 'showTeams'])->name('projects.teams');
    Route::post('/projects/{id}/teams', [ProjectController::class, 'addTeam'])->name('projects.teams.add');
    Route::delete('/projects/{id}/teams/{teamId}', [ProjectController::class, 'removeTeam'])->name('projects.teams.remove');

    // Sprints
    Route::get('/projects/{projectId}/sprints', [SprintController::class, 'index'])->name('sprints.index');
    Route::get('/projects/{projectId}/sprints/create', [SprintController::class, 'create'])->name('sprints.create');
    Route::post('/projects/{projectId}/sprints', [SprintController::class, 'store'])->name('sprints.store');
    Route::get('/sprints/{id}', [SprintController::class, 'show'])->name('sprints.show');
    Route::get('/sprints/{id}/edit', [SprintController::class, 'edit'])->name('sprints.edit');
    Route::put('/sprints/{id}', [SprintController::class, 'update'])->name('sprints.update');
    Route::delete('/sprints/{id}', [SprintController::class, 'destroy'])->name('sprints.destroy');

    // Tasks
    Route::get('/sprints/{sprintId}/tasks', [TaskController::class, 'index'])->name('tasks.index');
    Route::get('/sprints/{sprintId}/tasks/create', [TaskController::class, 'create'])->name('tasks.create');
    Route::post('/sprints/{sprintId}/tasks', [TaskController::class, 'store'])->name('tasks.store');
    Route::get('/tasks/{id}', [TaskController::class, 'show'])->name('tasks.show');
    Route::get('/tasks/{id}/edit', [TaskController::class, 'edit'])->name('tasks.edit');
    Route::put('/tasks/{id}', [TaskController::class, 'update'])->name('tasks.update');
    Route::delete('/tasks/{id}', [TaskController::class, 'destroy'])->name('tasks.destroy');
    Route::put('/tasks/{id}/status', [TaskController::class, 'updateStatus'])->name('tasks.status.update');
    Route::put('/tasks/{id}/assign', [TaskController::class, 'assignUser'])->name('tasks.assign');
    Route::get('/tasks/{id}/comments', [TaskController::class, 'showComments'])->name('tasks.comments');
    Route::post('/tasks/{id}/comments', [TaskController::class, 'addComment'])->name('tasks.comments.add');

    // Lists
    Route::get('/projects/{projectId}/lists', [ListController::class, 'index'])->name('lists.index');
    Route::get('/projects/{projectId}/lists/create', [ListController::class, 'create'])->name('lists.create');
    Route::post('/projects/{projectId}/lists', [ListController::class, 'store'])->name('lists.store');
    Route::get('/lists/{id}', [ListController::class, 'show'])->name('lists.show');
    Route::get('/lists/{id}/edit', [ListController::class, 'edit'])->name('lists.edit');
    Route::put('/lists/{id}', [ListController::class, 'update'])->name('lists.update');
    Route::delete('/lists/{id}', [ListController::class, 'destroy'])->name('lists.destroy');

    // Admin routes
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/users', [AuthController::class, 'index'])->name('admin.users.index');
        Route::get('/admin/users/{id}/edit', [AuthController::class, 'editUser'])->name('admin.users.edit');
        Route::put('/admin/users/{id}', [AuthController::class, 'updateUser'])->name('admin.users.update');
        Route::delete('/admin/users/{id}', [AuthController::class, 'destroyUser'])->name('admin.users.destroy');
});
});
?>