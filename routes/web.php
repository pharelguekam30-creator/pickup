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
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ChatController;

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
    Route::get('/admin/stats', [DashboardController::class, 'stats'])->name('admin.stats');
});

// ========================
// Services (consultation publique)
// ========================
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/carte-vidangeurs', [App\Http\Controllers\MapController::class, 'index'])->name('map.index');
Route::get('/map-test', [App\Http\Controllers\MapController::class, 'test'])->name('map.test');
Route::get('/carte', [App\Http\Controllers\MapController::class, 'embed'])->name('map.embed');
Route::get('/map-simple', [App\Http\Controllers\MapController::class, 'simple'])->name('map.simple');

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
// Confirmation menagere
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':menagere'])->group(function () {
    Route::post('reservations/{reservation}/confirm', [ReservationController::class, 'confirmComplete'])->name('reservations.confirm');
});

// ========================
// Admin : forcer paiement / annulation
// ========================
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function () {
    Route::post('admin/reservations/{reservation}/force-complete', [ReservationController::class, 'adminForceComplete'])->name('admin.forceComplete');
    Route::post('admin/reservations/{reservation}/force-cancel', [ReservationController::class, 'adminForceCancel'])->name('admin.forceCancel');
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
// Profil utilisateur connecté
// ========================
Route::middleware('auth')->get('/profile', [UserController::class, 'profile'])->name('profile');
Route::middleware('auth')->get('/profile/edit', [UserController::class, 'profileEdit'])->name('profile.edit');
Route::middleware('auth')->put('/profile', [UserController::class, 'profileUpdate'])->name('profile.update');

// ========================
// Paiements et portefeuille
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/deposit', [PaymentController::class, 'depositForm'])->name('payments.deposit.form');
    Route::post('/payments/deposit', [PaymentController::class, 'deposit'])->name('payments.deposit');
    Route::get('/payments/withdraw', [PaymentController::class, 'withdrawForm'])->name('payments.withdraw.form');
    Route::post('/payments/withdraw', [PaymentController::class, 'withdraw'])->name('payments.withdraw');
});

// ========================
// Chat entre menagere et vidangeur
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/chat/{reservation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{reservation}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{reservation}/messages', [ChatController::class, 'fetch'])->name('chat.fetch');
});

// ========================
// Vérification du compte
// ========================
Route::middleware('auth')->group(function () {
    Route::get('/verification', [AuthController::class, 'verificationForm'])->name('verification.form');
    Route::post('/verification', [AuthController::class, 'verify'])->name('verification.verify');
    Route::post('/verification/resend', [AuthController::class, 'resendCode'])->name('verification.resend');
});

// ========================
// Page de choix du rôle
// ========================
Route::get('/choose-role', function () {
    return view('auth.choose-role');
})->name('choix.role');
