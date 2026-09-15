<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SignalementController;
use App\Http\Controllers\InterventionController;
use Illuminate\Support\Facades\Route;

// Redirection d'accueil
Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Authentification (Invités uniquement)
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    
    Route::get('/forgot-password', function () {
    return view('auth.forgot-password'); })->name('password.request');
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

// Espace d'application protégé (Utilisateurs connectés)
Route::middleware(['auth'])->group(function () {
    // Déconnexion
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Tableau de bord
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Notifications
    Route::post('/notifications/mark-read/{id}', [DashboardController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    // Signalements
    Route::get('/signalements', [SignalementController::class, 'index'])->name('signalements.index');
    Route::get('/signalements/nouveau', [SignalementController::class, 'create'])->name('signalements.create');
    Route::post('/signalements', [SignalementController::class, 'store'])->name('signalements.store');
    Route::get('/signalements/{id}', [SignalementController::class, 'show'])->name('signalements.show');

    // Interventions (Sécurisées par Policy dans le contrôleur)
    Route::post('/interventions', [InterventionController::class, 'store'])->name('interventions.store');
});