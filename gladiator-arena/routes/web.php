<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GameController;
Route::get('/', [GameController::class, 'index'])->name('game.index');
Route::post('/attack', [GameController::class, 'attack'])->name('game.attack');
Route::post('/restart', [GameController::class, 'restart'])->name('game.restart');