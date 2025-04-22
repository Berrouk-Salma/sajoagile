<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\ProjectMemberController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // Profile routes
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Project routes
    Route::resource('projects', ProjectController::class);

    Route::get('/search-users', [ProjectMemberController::class, 'searchUsers'])->name('users.search');
    Route::post('/invite-user', [ProjectMemberController::class, 'inviteUsers'])->name('projects.invite');
    Route::get('/mes-invitations', [ProjectMemberController::class, 'showInvitations'])->name('projects.invitations');
    Route::post('/mes-invitations/{projectId}/respond', [ProjectMemberController::class, 'respondToInvitation'])->name('projects.respond');
});

require __DIR__.'/auth.php';
