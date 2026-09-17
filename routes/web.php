<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SignalementController;
use App\Http\Controllers\InterventionController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| 1. Redirection Racine
|--------------------------------------------------------------------------
| Si l'utilisateur est connecté -> Dashboard, sinon -> Page de connexion.
*/
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

/*
|--------------------------------------------------------------------------
| 2. Routes Publiques & Authentification (Invités uniquement - Middleware guest)
|--------------------------------------------------------------------------
*/
Route::middleware('guest')->group(function () {
    // Connexion
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);

    // Inscription (Rôle demandeur attribué par défaut)
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Mot de passe oublié
    Route::get('/forgot-password', function () {
        return view('auth.forgot-password');
    })->name('password.request');
});

/*
|--------------------------------------------------------------------------
| 3. Espace Protégé (Utilisateurs Authentifiés - Middleware auth)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Tableau de bord principal (KPIs selon le rôle)
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Gestion des notifications (lecture)
    Route::post('/notifications/mark-read/{id}', [DashboardController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Module Signalements
    Route::get('/signalements', [SignalementController::class, 'index'])->name('signalements.index');
    Route::get('/signalements/nouveau', [SignalementController::class, 'create'])->name('signalements.create');
    Route::post('/signalements', [SignalementController::class, 'store'])->name('signalements.store');
    Route::get('/signalements/{id}', [SignalementController::class, 'show'])->name('signalements.show');

    // Module Interventions (Strictement réservé au rôle Technicien)
    Route::post('/interventions', [InterventionController::class, 'store'])
        ->middleware('role:technicien')
        ->name('interventions.store');
});