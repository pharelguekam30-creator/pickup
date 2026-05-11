<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\AutorisationController;
use App\Http\Controllers\RoleAutorisationController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\AvisController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\DashboardController;

// ========================
// Accueil
// ========================
Route::get('/', [HomeController::class, 'index'])->name('home');

// ========================
// Authentification
// ========================
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ========================
// Dashboards par rôle
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':menagere'])->group(function () {
    Route::get('/menagere/dashboard', [DashboardController::class, 'menagere'])->name('menagere.dashboard');
    Route::get('reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('reservations', [ReservationController::class, 'store'])->name('reservations.store');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':vidangeur'])->group(function () {
    Route::get('/vidangeur/dashboard', [DashboardController::class, 'vidangeur'])->name('vidangeur.dashboard');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])
        ->name('dashboard');
});

// ========================
// Services (consultation publique)
// ========================
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');

// ========================
// Routes RESTful protégées (admin)
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('autorisations', AutorisationController::class);
    Route::resource('role_autorisations', RoleAutorisationController::class);
    Route::resource('services', ServiceController::class)->except(['index']);
    Route::resource('reservations', ReservationController::class)->except(['create', 'store']);
    Route::resource('accounts', AccountController::class);
});

// ========================
// Routes Vidangeur
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':vidangeur'])->group(function () {
    Route::post('reservations/{reservation}/accept', [ReservationController::class, 'accept'])->name('reservations.accept');
    Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('reservations/{reservation}/complete', [ReservationController::class, 'complete'])->name('reservations.complete');
});

// ========================
// Avis (seulement pour ménagère)
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':menagere'])->group(function () {
    Route::get('/services/{service}/avis/create', [AvisController::class, 'create'])->name('avis.create');
    Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');
    Route::get('/mes-avis', [AvisController::class, 'index'])->name('avis.index');
});

// ========================
// Page de choix du rôle
// ========================
Route::get('/choose-role', function () {
    return view('auth.choose-role');
})->name('choix.role');
