<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InterventionController;
use App\Http\Controllers\SignalementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/notifications/mark-read/{id}', [DashboardController::class, 'markAsRead'])->name('notifications.markRead');
    Route::post('/notifications/mark-all-read', [DashboardController::class, 'markAllAsRead'])->name('notifications.markAllRead');

    Route::get('/signalements', [SignalementController::class, 'index'])->name('signalements.index');
    Route::get('/signalements/nouveau', [SignalementController::class, 'create'])->name('signalements.create');
    Route::post('/signalements', [SignalementController::class, 'store'])->name('signalements.store');
    Route::get('/signalements/{id}', [SignalementController::class, 'show'])->name('signalements.show');
    Route::delete('/signalements/{id}', [SignalementController::class, 'destroy'])->name('signalements.destroy');

    Route::post('/interventions', [InterventionController::class, 'store'])->name('interventions.store');
});