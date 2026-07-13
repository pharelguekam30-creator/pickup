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

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.submit');

Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->get('/debug/test-mail', function () {
    try {
        $sent = \App\Helpers\MailHelper::sendEmail(auth()->user()->email, 'Test PICKUP Railway', 'Ceci est un email de test depuis PICKUP (Railway).');
        return $sent ? 'Email envoye avec succes a ' . auth()->user()->email : 'ERREUR: Echec envoi email';
    } catch (\Exception $e) {
        return 'ERROR: ' . $e->getMessage();
    }
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':menagere'])->group(function () {
    Route::get('/menagere/dashboard', [DashboardController::class, 'menagere'])->name('menagere.dashboard');
    Route::get('reservations/create', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('reservations', [ReservationController::class, 'store'])->name('reservations.store');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':vidangeur'])->group(function () {
    Route::get('/vidangeur/dashboard', [DashboardController::class, 'vidangeur'])->name('vidangeur.dashboard');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/admin/stats', [DashboardController::class, 'stats'])->name('admin.stats');
});

Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/carte-vidangeurs', [App\Http\Controllers\MapController::class, 'index'])->name('map.index');
Route::get('/map-test', [App\Http\Controllers\MapController::class, 'test'])->name('map.test');
Route::get('/carte', [App\Http\Controllers\MapController::class, 'embed'])->name('map.embed');
Route::get('/map-simple', [App\Http\Controllers\MapController::class, 'simple'])->name('map.simple');

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function () {
    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);
    Route::resource('autorisations', AutorisationController::class);
    Route::resource('role_autorisations', RoleAutorisationController::class);
    Route::resource('services', ServiceController::class)->except(['index']);
    Route::resource('reservations', ReservationController::class)->except(['create', 'store']);
    Route::resource('accounts', AccountController::class);
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':vidangeur'])->group(function () {
    Route::post('reservations/{reservation}/accept', [ReservationController::class, 'accept'])->name('reservations.accept');
    Route::post('reservations/{reservation}/cancel', [ReservationController::class, 'cancel'])->name('reservations.cancel');
    Route::post('reservations/{reservation}/complete', [ReservationController::class, 'complete'])->name('reservations.complete');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':menagere'])->group(function () {
    Route::post('reservations/{reservation}/confirm', [ReservationController::class, 'confirmComplete'])->name('reservations.confirm');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function () {
    Route::post('admin/reservations/{reservation}/force-complete', [ReservationController::class, 'adminForceComplete'])->name('admin.forceComplete');
    Route::post('admin/reservations/{reservation}/force-cancel', [ReservationController::class, 'adminForceCancel'])->name('admin.forceCancel');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':menagere'])->group(function () {
    Route::get('/services/{service}/avis/create', [AvisController::class, 'create'])->name('avis.create');
    Route::post('/avis', [AvisController::class, 'store'])->name('avis.store');
    Route::get('/mes-avis', [AvisController::class, 'index'])->name('avis.index');
});

Route::middleware('auth')->get('/profile', [UserController::class, 'profile'])->name('profile');
Route::middleware('auth')->get('/profile/edit', [UserController::class, 'profileEdit'])->name('profile.edit');
Route::middleware('auth')->put('/profile', [UserController::class, 'profileUpdate'])->name('profile.update');

Route::middleware('auth')->group(function () {
    Route::get('/payments', [PaymentController::class, 'index'])->name('payments.index');
    Route::get('/payments/deposit', [PaymentController::class, 'depositForm'])->name('payments.deposit.form');
    Route::post('/payments/deposit', [PaymentController::class, 'deposit'])->name('payments.deposit');
    Route::get('/payments/withdraw', [PaymentController::class, 'withdrawForm'])->name('payments.withdraw.form');
    Route::post('/payments/withdraw', [PaymentController::class, 'withdraw'])->name('payments.withdraw');
});

Route::middleware('auth')->group(function () {
    Route::get('/chat/{reservation}', [ChatController::class, 'show'])->name('chat.show');
    Route::post('/chat/{reservation}/send', [ChatController::class, 'send'])->name('chat.send');
    Route::get('/chat/{reservation}/messages', [ChatController::class, 'fetch'])->name('chat.fetch');
});

Route::middleware('auth')->group(function () {
    Route::get('/verification', [AuthController::class, 'verificationForm'])->name('verification.form');
    Route::post('/verification', [AuthController::class, 'verify'])->name('verification.verify');
    Route::post('/verification/resend', [AuthController::class, 'resendCode'])->name('verification.resend');
});

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->get('/debug/mail', function () {
    $key = \App\Helpers\MailHelper::getApiKey();
    $log = '';
    $sent = \App\Helpers\MailHelper::sendEmail(auth()->user()->email, 'Test PICKUP API', 'Test via API Brevo', $log);
    return '<pre>Key defined: ' . ($key ? 'OUI (longueur: '.strlen($key).')' : 'NON') . "\nResult: " . ($sent ? 'OK' : "ECHEC\nLog: $log") . '</pre>';
})->name('debug.mail');

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->get('/debug/env', function () {
    $railwayVars = ['RAILWAY_API_TOKEN', 'RAILWAY_PROJECT_ID', 'RAILWAY_ENVIRONMENT_ID', 'RAILWAY_SERVICE_ID', 'RAILWAY_DEPLOYMENT_ID', 'BREVO_API_KEY', 'MAIL_MAILER', 'MAIL_FROM_ADDRESS'];
    $output = '';
    foreach ($railwayVars as $v) {
        $val = env($v);
        if ($v === 'RAILWAY_API_TOKEN' && $val) {
            $val = substr($val, 0, 10) . '...';
        }
        $output .= "$v = " . ($val ?: '(not set)') . "\n";
    }
    $output .= "\nAPP_ENV = " . env('APP_ENV') . "\n";
    $output .= "All env keys:\n" . implode("\n", array_keys($_ENV));
    return '<pre>' . htmlspecialchars($output) . '</pre>';
})->name('debug.env');

Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->match(['get', 'post'], '/admin/brevo-key', function (\Illuminate\Http\Request $request) {
    if ($request->isMethod('post') || $request->has('set_key')) {
        $key = $request->input('key', '');
        if ($key) {
            file_put_contents(storage_path('app/brevo_key.txt'), $key);
            return 'OK - Cle enregistree (' . strlen($key) . ' caracteres)';
        }
        return 'ERREUR: cle vide';
    }
    $current = file_exists(storage_path('app/brevo_key.txt')) ? file_get_contents(storage_path('app/brevo_key.txt')) : '';
    return '<form method=post><input type=hidden name=_token value="'.csrf_token().'"><label>Cle API Brevo :</label><br><input type=text name=key value="'.htmlspecialchars($current).'" size=60><br><br><button type=submit>Enregistrer</button></form>';
})->name('admin.brevo-key');

Route::get('/choose-role', function () {
    return view('auth.choose-role');
})->name('choix.role');

// Plans de collecte (public)
Route::get('/plans', [\App\Http\Controllers\SubscriptionController::class, 'plans'])->name('subscriptions.plans');

// Souscription (ménagère)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':menagere'])->group(function () {
    Route::get('/plans/{plan}/subscribe', [\App\Http\Controllers\SubscriptionController::class, 'subscribeForm'])->name('subscriptions.subscribe.form');
    Route::post('/subscriptions', [\App\Http\Controllers\SubscriptionController::class, 'subscribe'])->name('subscriptions.subscribe');
    Route::get('/my-subscriptions', [\App\Http\Controllers\SubscriptionController::class, 'mySubscriptions'])->name('subscriptions.my');
    Route::post('/subscriptions/{subscription}/cancel', [\App\Http\Controllers\SubscriptionController::class, 'cancel'])->name('subscriptions.cancel');
});

// Collectes d'abonnement (ménagère + vidangeur)
Route::middleware('auth')->group(function () {
    Route::get('/subscriptions/{subscription}/collections', [\App\Http\Controllers\SubscriptionController::class, 'myCollections'])->name('subscriptions.collections');
});

// Abonnements du vidangeur
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':vidangeur'])->group(function () {
    Route::get('/vidangeur/abonnements', [\App\Http\Controllers\SubscriptionController::class, 'vidangeurSubscriptions'])->name('subscriptions.vidangeur');
});

// Compléter une collecte (vidangeur)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':vidangeur'])->group(function () {
    Route::post('/subscriptions/collections/{collection}/complete', [\App\Http\Controllers\SubscriptionController::class, 'completeCollection'])->name('subscriptions.collections.complete');
    Route::post('/subscriptions/{subscription}/complete-month', [\App\Http\Controllers\SubscriptionController::class, 'completeMonth'])->name('subscriptions.month.complete');
});

// Confirmer le mois (ménagère)
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':menagere'])->group(function () {
    Route::post('/subscriptions/{subscription}/confirm-month', [\App\Http\Controllers\SubscriptionController::class, 'confirmMonth'])->name('subscriptions.month.confirm');
});

// Admin CRUD plans
Route::middleware(['auth', \App\Http\Middleware\RoleMiddleware::class.':admin'])->group(function () {
    Route::resource('admin/plans', \App\Http\Controllers\Admin\PlanController::class)->except(['show'])->names([
        'index' => 'admin.plans.index',
        'create' => 'admin.plans.create',
        'store' => 'admin.plans.store',
        'edit' => 'admin.plans.edit',
        'update' => 'admin.plans.update',
        'destroy' => 'admin.plans.destroy',
    ]);
    Route::get('/admin/subscriptions', [\App\Http\Controllers\SubscriptionController::class, 'adminDisputes'])->name('admin.subscriptions');
    Route::post('/admin/subscriptions/{subscription}/force-pay', [\App\Http\Controllers\SubscriptionController::class, 'adminForcePay'])->name('admin.subscriptions.force-pay');
    Route::post('/admin/subscriptions/{subscription}/force-cancel-month', [\App\Http\Controllers\SubscriptionController::class, 'adminForceCancelMonth'])->name('admin.subscriptions.force-cancel-month');
});
