<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EntryController;
use App\Http\Controllers\AuthController;

Route::get('/', function () {
    return view('welcome');
});

Route::post('store', [EntryController::class, 'store']);
Route::get('entries', [EntryController::class, 'index']);


// Routes d'authentification
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']); // Inscription
    Route::post('/login', [AuthController::class, 'login']); // Connexion
    Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum'); // Déconnexion
    Route::get('/user', [AuthController::class, 'user'])->middleware('auth:sanctum'); // Récupérer l'utilisateur connecté
});