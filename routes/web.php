<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


/* Admin Panel */

Route::prefix('admin')->group(function () {

    Route::get('/login', [AuthController::class, 'login'])->name('admin.login');
    Route::post('/login_submit', [AuthController::class, 'login_submit'])->name('admin.submit');

    Route::middleware([IsAdmin::class])->group(function () {

        Route::get('/', [HomeController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/dashboard', [HomeController::class, 'dashboard'])->name('admin.dashboard');

        Route::get('profile', [ProfileController::class, 'index'])->name('admin.profile');
        Route::post('profile/update', [ProfileController::class, 'update'])->name('admin.profile.update');

        Route::get('change-password', [HomeController::class, 'change_password']);
        Route::post('update-password', [HomeController::class, 'update_password']);
    });
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
