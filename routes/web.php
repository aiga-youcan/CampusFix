<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SignalementController;
use App\Http\Controllers\InterventionController;
use Illuminate\Support\Facades\Route;

// Redirection accueil vers login ou dashboard
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentification
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
// مسارات تعليم الإشعارات كمقروءة
    Route::post('/notifications/mark-read/{id}', [DashboardController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllAsRead'])->name('notifications.markAllRead');
    
// Espace sécurisé
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // CRUD Signalements
    Route::delete('/signalements/{id}', [SignalementController::class, 'destroy'])->name('signalements.destroy');
    Route::get('/signalements', [SignalementController::class, 'index'])->name('signalements.index');
    Route::get('/signalements/nouveau', [SignalementController::class, 'create'])->name('signalements.create');
    Route::post('/signalements', [SignalementController::class, 'store'])->name('signalements.store');
    Route::get('/signalements/{signalement}', [SignalementController::class, 'show'])->name('signalements.show');
    Route::post('/signalements/{signalement}/retriage', [SignalementController::class, 'retriage'])->name('signalements.retriage');

    // Interventions
    Route::post('/interventions', [InterventionController::class, 'store'])->name('interventions.store');
});
