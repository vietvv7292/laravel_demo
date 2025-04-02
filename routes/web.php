<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use App\Http\Controllers\QueueController;


use App\Http\Controllers\DemoController;

Route::get('/demo', [DemoController::class, 'index']);
Route::get('/demo/queue', [DemoController::class, 'queueDemo']);
Route::get('/demo/event', [DemoController::class, 'eventDemo']);
Route::get('/demo/auth', [DemoController::class, 'authDemo']);


Route::get('/queue-demo', [QueueController::class, 'sendEmail']);

use App\Http\Controllers\DocsController;

Route::get('/docs/queue', [DocsController::class, 'queueDoc'])->name('docs.queue');
Route::get('/docs/event', [DocsController::class, 'eventDoc'])->name('docs.event');
Route::get('/docs/auth', [DocsController::class, 'authDoc'])->name('docs.auth');


Route::get('/docs/queue/guide', [DocsController::class, 'queueGuideDoc'])->name('docs.queue.guide');
Route::get('/docs/event/guide', [DocsController::class, 'eventGuideDoc'])->name('docs.event.guide');
Route::get('/docs/auth/guide', [DocsController::class, 'authGuideDoc'])->name('docs.auth.guide');

// Route::get('/', function () {
//     return Inertia::render('Welcome');
// })->name('home');

// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// require __DIR__.'/settings.php';
// require __DIR__.'/auth.php';
