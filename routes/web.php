<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\LogController;
use App\Http\Controllers\KeuzedeelController;
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
    Route::get('/keuzedelen/{keuzedeel}', [KeuzedeelController::class, 'show'])->name('keuzedelen.show');
    Route::get('/mijn-inschrijvingen', [KeuzedeelController::class, 'mijnInschrijvingen'])->name('mijn-inschrijvingen');
    Route::post('/keuzedelen/{keuzedeel}/inschrijven', [KeuzedeelController::class, 'inschrijven'])->name('keuzedelen.inschrijven');
    Route::delete('/keuzedelen/{keuzedeel}/uitschrijven', [KeuzedeelController::class, 'uitschrijven'])->name('keuzedelen.uitschrijven');
});

// Admin Routes
Route::middleware(['auth', 'verified', 'is_admin'])->group(function () {
    Route::get('/logs', [LogController::class, 'index'])->name('logs.index');
    Route::post('/logs/clear', [LogController::class, 'clear'])->name('logs.clear');
    Route::get('/logs/download', [LogController::class, 'download'])->name('logs.download');
});

require __DIR__.'/auth.php';
