<?php

use App\Http\Controllers\CalculateCostController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MatchSkillsController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::post('calculate-cost', CalculateCostController::class)->name('calculate-cost');
Route::post('match-skills', MatchSkillsController::class)->name('match-skills');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
