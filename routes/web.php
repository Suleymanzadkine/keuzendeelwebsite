<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ElectiveController;
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

    Route::get('/electives', [ElectiveController::class, 'index'])->name('electives.index');
    Route::post('/electives', [ElectiveController::class, 'store'])->name('electives.store');
});

require __DIR__.'/auth.php';
