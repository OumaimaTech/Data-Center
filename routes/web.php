<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController;

// Page d'accueil publique
Route::get('/', function () {
    return view('welcome');
})->name('home');

// Routes d'authentification
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

// Routes protégées par authentification
Route::middleware(['auth'])->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Ressources - Accessible à tous les utilisateurs authentifiés
    Route::resource('resources', ResourceController::class);
    
    // Réservations
    Route::resource('reservations', ReservationController::class);
    Route::post('/reservations/{reservation}/approve', [ReservationController::class, 'approve'])
        ->name('reservations.approve')
        ->middleware('role:Responsable technique,Administrateur');
    Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])
        ->name('reservations.reject')
        ->middleware('role:Responsable technique,Administrateur');
    
    // Notifications
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
    Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    
    // Routes Admin - Accessible uniquement aux administrateurs
    Route::middleware(['role:Administrateur'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
    });
    
    // Routes Responsable technique - Accessible aux responsables et admins
    Route::middleware(['role:Responsable technique,Administrateur'])->group(function () {
        // Gestion des ressources (create, edit, delete)
        // Déjà géré par le resource controller avec vérification dans le controller
    });
});
