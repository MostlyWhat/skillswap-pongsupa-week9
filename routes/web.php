<?php

use App\Http\Controllers\CalculateCostController;
use App\Http\Controllers\SkillController;
use Illuminate\Support\Facades\Route;

Route::get('/', [SkillController::class, 'index'])->name('home');
Route::post('calculate-cost', CalculateCostController::class)->name('calculate-cost');
Route::post('match-skills', [SkillController::class, 'match'])->name('match-skills');
Route::post('skills', [SkillController::class, 'store'])->name('skills.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::inertia('dashboard', 'Dashboard')->name('dashboard');
});

require __DIR__.'/settings.php';
