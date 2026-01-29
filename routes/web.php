<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\KeuzedeelController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::get('/keuzedelen', [KeuzedeelController::class, 'index'])->name('keuzedelen.index');
    Route::get('/keuzedelen/create', [KeuzedeelController::class, 'create'])->name('keuzedelen.create');
    Route::get('/keuzedelen/{keuzedeel}', [KeuzedeelController::class, 'show'])->name('keuzedelen.show');
    Route::get('/keuzedelen/{keuzedeel}/edit', [KeuzedeelController::class, 'edit'])->name('keuzedelen.edit');
    Route::patch('/keuzedelen/{keuzedeel}', [KeuzedeelController::class, 'update'])->name('keuzedelen.update');
    Route::delete('/keuzedelen/{keuzedeel}', [KeuzedeelController::class, 'destroy'])->name('keuzedelen.destroy');
    Route::post('/keuzedelen/{keuzedeel}/toggle-active', [KeuzedeelController::class, 'toggleActive'])->name('keuzedelen.toggle-active');
    Route::get('/mijn-inschrijvingen', [KeuzedeelController::class, 'mijnInschrijvingen'])->name('mijn-inschrijvingen');
    Route::post('/keuzedelen/{keuzedeel}/inschrijven', [KeuzedeelController::class, 'inschrijven'])->name('keuzedelen.inschrijven');
    Route::delete('/keuzedelen/{keuzedeel}/uitschrijven', [KeuzedeelController::class, 'uitschrijven'])->name('keuzedelen.uitschrijven');
    Route::post('/keuzedelen', [KeuzedeelController::class, 'store'])->name('keuzedelen.store');
    Route::delete('/inschrijvingen/{inschrijving}/verwijderen', [KeuzedeelController::class, 'verwijderLeerling'])->name('inschrijvingen.verwijderen');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'is_admin'])->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
    Route::get('/logs/download', [LogController::class, 'download'])->name('logs.download');

    // Admin action: remove all enrollments of a user for a keuzedeel
    Route::delete('/inschrijvingen/{keuzedeel}/user/{user}/verwijderen', [KeuzedeelController::class, 'verwijderLeerlingVoorGebruiker'])->name('inschrijvingen.verwijderen.user');

    // Notifications
    Route::get('/notifications', [\App\Http\Controllers\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('/notifications/mark-all-read', [\App\Http\Controllers\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    // Role management
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('roles', RoleController::class)->except(['show']);
        Route::get('users', [UserController::class, 'index'])->name('users.index');
        Route::get('users/{user}/roles', [UserController::class, 'editRoles'])->name('users.edit-roles');
        Route::post('users/{user}/roles', [UserController::class, 'updateRoles'])->name('users.update-roles');
    });
});

require __DIR__.'/auth.php';
