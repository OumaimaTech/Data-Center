<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ResourceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/guest/resources', [App\Http\Controllers\GuestController::class, 'resources'])->name('guest.resources');
Route::get('/guest/resources/{resource}', [App\Http\Controllers\GuestController::class, 'show'])->name('guest.show');
Route::get('/guest/info', [App\Http\Controllers\GuestController::class, 'info'])->name('guest.info');

Route::get('/account-request', [App\Http\Controllers\AccountRequestController::class, 'create'])->name('account-request.create');
Route::post('/account-request', [App\Http\Controllers\AccountRequestController::class, 'store'])->name('account-request.store');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register']);

Route::middleware(['auth'])->group(function () {
    
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Routes pour les ressources - ordre important: routes spécifiques avant routes avec paramètres
    Route::get('/resources', [ResourceController::class, 'index'])->name('resources.index');
    
    Route::middleware(['role:Responsable technique,Administrateur'])->group(function () {
        Route::get('/resources/create', [ResourceController::class, 'create'])->name('resources.create');
        Route::post('/resources', [ResourceController::class, 'store'])->name('resources.store');
        Route::get('/resources/{resource}/edit', [ResourceController::class, 'edit'])->name('resources.edit');
        Route::put('/resources/{resource}', [ResourceController::class, 'update'])->name('resources.update');
        Route::patch('/resources/{resource}/toggle-status', [ResourceController::class, 'toggleStatus'])->name('resources.toggle-status');
        Route::delete('/resources/{resource}', [ResourceController::class, 'destroy'])->name('resources.destroy');
    });
    
    Route::get('/resources/{resource}', [ResourceController::class, 'show'])->name('resources.show');
    
    // Routes de réservations - Consultation pour tous les rôles autorisés
    Route::middleware(['role:Utilisateur interne,Responsable technique,Administrateur'])->group(function () {
        Route::get('/reservations', [ReservationController::class, 'index'])->name('reservations.index');
        Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    });
    
    // Routes de réservations - Création UNIQUEMENT pour les Utilisateurs internes
    Route::middleware(['role:Utilisateur interne'])->group(function () {
        Route::get('/reservations/history', [ReservationController::class, 'history'])->name('reservations.history');
        Route::get('/reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
        Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
        Route::delete('/reservations/{reservation}', [ReservationController::class, 'destroy'])->name('reservations.destroy');
    });
    
    Route::post('/reservations/{reservation}/approve', [ReservationController::class, 'approve'])
        ->name('reservations.approve')
        ->middleware('role:Responsable technique,Administrateur');
    Route::post('/reservations/{reservation}/reject', [ReservationController::class, 'reject'])
        ->name('reservations.reject')
        ->middleware('role:Responsable technique,Administrateur');
    
    Route::middleware(['role:Utilisateur interne,Responsable technique,Administrateur'])->group(function () {
        Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
        Route::patch('/notifications/{notification}/mark-read', [NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [NotificationController::class, 'markAllAsRead'])->name('notifications.mark-all-read');
        Route::delete('/notifications/{notification}', [NotificationController::class, 'destroy'])->name('notifications.destroy');
    });
    
    Route::middleware(['role:Utilisateur interne,Responsable technique,Administrateur'])->group(function () {
        Route::get('/incidents', [App\Http\Controllers\IncidentController::class, 'index'])->name('incidents.index');
        Route::get('/incidents/create', [App\Http\Controllers\IncidentController::class, 'create'])->name('incidents.create');
        Route::post('/incidents', [App\Http\Controllers\IncidentController::class, 'store'])->name('incidents.store');
        Route::get('/incidents/{incident}', [App\Http\Controllers\IncidentController::class, 'show'])->name('incidents.show');
        Route::delete('/incidents/{incident}', [App\Http\Controllers\IncidentController::class, 'destroy'])->name('incidents.destroy');
    });

    Route::middleware(['role:Responsable technique,Administrateur'])->group(function () {
        Route::patch('/incidents/{incident}/status', [App\Http\Controllers\IncidentController::class, 'updateStatus'])->name('incidents.update-status');
        Route::post('/incidents/{incident}/resolve', [App\Http\Controllers\IncidentController::class, 'resolve'])->name('incidents.resolve');
    });
    
    Route::middleware(['role:Administrateur'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::patch('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
        
        Route::get('/maintenance', [App\Http\Controllers\Admin\MaintenanceController::class, 'index'])->name('maintenance.index');
        Route::get('/maintenance/create', [App\Http\Controllers\Admin\MaintenanceController::class, 'create'])->name('maintenance.create');
        Route::post('/maintenance', [App\Http\Controllers\Admin\MaintenanceController::class, 'store'])->name('maintenance.store');
        Route::delete('/maintenance/{maintenance}', [App\Http\Controllers\Admin\MaintenanceController::class, 'destroy'])->name('maintenance.destroy');
        
        Route::get('/statistics', [App\Http\Controllers\Admin\StatisticsController::class, 'index'])->name('statistics.index');
        
        Route::get('/account-requests', [App\Http\Controllers\AccountRequestController::class, 'index'])->name('account-requests.index');
        Route::post('/account-requests/{accountRequest}/approve', [App\Http\Controllers\AccountRequestController::class, 'approve'])->name('account-requests.approve');
        Route::post('/account-requests/{accountRequest}/reject', [App\Http\Controllers\AccountRequestController::class, 'reject'])->name('account-requests.reject');
    });
});
