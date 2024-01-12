<?php

use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/login', function () {
//     return Inertia::render('Auth/Login');
// });

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/manage-user', function () {
    return Inertia::render('Panel/Manage-User');
})->middleware(['auth', 'verified'])->name('manage-user');

Route::get('/manage-role', function () {
    return Inertia::render('Panel/Manage-Role');
})->middleware(['auth', 'verified'])->name('manage-role');

Route::get('/manage-panel', function () {
    return Inertia::render('Panel/Manage-Panel');
})->middleware(['auth', 'verified'])->name('manage-panel');

Route::get('/manage-user-panels', function () {
    return Inertia::render('Panel/Manage-User-Panels');
})->middleware(['auth', 'verified'])->name('manage-user-panels');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return Inertia::render('Panel/Home');
    })->name('dashboard');
});
