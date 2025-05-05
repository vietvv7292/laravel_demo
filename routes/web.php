<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\QueueController;


use App\Http\Controllers\DemoController;

Route::get('/demo', [DemoController::class, 'index']);
Route::get('/', [DemoController::class, 'index']);
Route::get('/demo/queue', [DemoController::class, 'queueDemo']);
Route::get('/demo/event', [DemoController::class, 'eventDemo']);
Route::get('/demo/auth', [DemoController::class, 'authDemo']);
Route::get('/demo/broadcasting', [DemoController::class, 'broadcastingDemo']);
// Route::get('/demo/message', [DemoController::class, 'messageDemo']);

Route::get('/demo/message', [DemoController::class, 'messageForm']);
Route::post('/demo/message', [DemoController::class, 'messageSend']);



Route::get('/queue-demo', [QueueController::class, 'sendEmail']);

use App\Http\Controllers\DocsController;

Route::get('/docs/queue', [DocsController::class, 'queueDoc'])->name('docs.queue');
Route::get('/docs/event', [DocsController::class, 'eventDoc'])->name('docs.event');
Route::get('/docs/authenticate', [DocsController::class, 'authDoc'])->name('docs.authenticate');
Route::get('/docs/broadcasting', [DocsController::class, 'broadcastingDemo']);

Route::get('/docs/queue/guide', [DocsController::class, 'queueGuideDoc'])->name('docs.queue.guide');
Route::get('/docs/event/guide', [DocsController::class, 'eventGuideDoc'])->name('docs.event.guide');
Route::get('/docs/auth/guide', [DocsController::class, 'authGuideDoc'])->name('docs.auth.guide');


// authentication
use App\Http\Controllers\AuthController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

// Các route cần đăng nhập
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [AuthController::class, 'showdashboard'])->name('dashboard');
    Route::get('/users/{id}/edit', [AuthController::class, 'edit'])->name('user.edit');
    Route::post('/users/{id}', [AuthController::class, 'update'])->name('user.update');
});




// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';


// define('DB_DRIVER', 'mysqli');
// define('DB_HOSTNAME', 'localhost');
// define('DB_USERNAME', 'vietvv');
// define('DB_PASSWORD', '12345678');
// define('DB_SSL_KEY', '');
// define('DB_SSL_CERT', '');
// define('DB_SSL_CA', '');
// define('DB_DATABASE', 'opencart3');
// define('DB_PORT', '3306');
// define('DB_PREFIX', 'oc_');