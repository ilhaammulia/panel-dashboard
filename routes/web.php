<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\PanelController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UserPanelController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

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

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/', function () {
        if (Auth::user()->role_id == 'admin') {
            return redirect(route('admin.dashboard'));
        }
        return Inertia::render('Dashboard');
    });
    Route::middleware(['must.admin'])->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
        Route::get('/userspanels', [UserPanelController::class, 'index'])->name('admin.user.panels');

        Route::resource('users', UserController::class);
        Route::resource('panels', PanelController::class);
        Route::resource('users.panels', UserPanelController::class);

        Route::post('/upload/panels/icon', [PanelController::class, 'upload'])->name('admin.panels.upload.icon');
        Route::delete('/delete/panels/icon', [PanelController::class, 'remove_upload'])->name('admin.panels.delete.icon');
        Route::delete('/delete/bulk/users', [UserController::class, 'delete_many'])->name('admin.users.delete.bulk');
        Route::delete('/delete/bulk/panels', [PanelController::class, 'delete_many'])->name('admin.panels.delete.bulk');
    });
});
