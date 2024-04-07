<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\StandardController;
use App\Http\Controllers\Admin\StudentController;
use App\Http\Controllers\Admin\SubjectController;
use App\Http\Controllers\Admin\TeacherController;
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

        Route::get('standards/fetch', [StandardController::class, 'fetch'])->name('standards.fetch');
        Route::get('standards/chage-status', [StandardController::class, 'chage_status'])->name('standards.chage_status');
        Route::resource('standards', StandardController::class);

        Route::get('subjects/fetch', [SubjectController::class, 'fetch'])->name('subjects.fetch');
        Route::get('subjects/chage-status', [SubjectController::class, 'chage_status'])->name('subjects.chage_status');
        Route::resource('subjects', SubjectController::class);

        Route::get('batchs/fetch', [BatchController::class, 'fetch'])->name('batchs.fetch');
        Route::get('batchs/chage-status', [BatchController::class, 'chage_status'])->name('batchs.chage_status');
        Route::resource('batchs', BatchController::class);

        Route::get('students/fetch', [StudentController::class, 'fetch'])->name('students.fetch');
        Route::get('students/chage-status', [StudentController::class, 'chage_status'])->name('students.chage_status');
        Route::resource('students', StudentController::class);
        Route::get('fetch_standard_subject', [StudentController::class, 'fetch_standard_subject'])->name('fetch.standard.subject');

        Route::get('teachers/fetch', [TeacherController::class, 'fetch'])->name('teachers.fetch');
        Route::get('teachers/chage-status', [TeacherController::class, 'chage_status'])->name('teachers.chage_status');
        Route::resource('teachers', TeacherController::class);







    });
});
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
