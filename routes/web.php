<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\KeuzedeelController;
use App\Http\Controllers\UserController;
use App\Models\Keuzedeel;

Route::get('/', function () {
    $keuzedelen = Keuzedeel::all();
    return view('index', compact('keuzedelen'));
});
Route::get('/about', function () {
    return view('about');
});
Route::get('/contact', function () {
    return view('contact');
});
//Check2keer!!!
Route::middleware(['auth'])->group(function () {

    // Overzicht alle keuzedelen
    Route::get('/keuzedelen', [KeuzedeelController::class, 'index'])->name('keuzedelen.index');

    // Details van een keuzedeel
    Route::get('/keuzedelen/{keuzedeel}', [KeuzedeelController::class, 'show'])->name('keuzedelen.show');

    // Inschrijven
    Route::post('/keuzedelen/{keuzedeel}/inschrijven', [KeuzedeelController::class, 'inschrijven'])->name('keuzedelen.inschrijven');

    // Uitschrijven
    Route::post('/keuzedelen/{keuzedeel}/uitschrijven', [KeuzedeelController::class, 'uitschrijven'])->name('keuzedelen.uitschrijven');

    // Mijn inschrijvingen
    Route::get('/mijn-inschrijvingen', [KeuzedeelController::class, 'mijnInschrijvingen'])->name('keuzedelen.mijn-inschrijvingen');
});
