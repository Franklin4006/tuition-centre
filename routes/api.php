<?php

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::post('student-login', [ApiController::class, 'student_login']);
Route::get('student-info', [ApiController::class, 'student_info']);
Route::get('classes-info', [ApiController::class, 'classes_info']);
Route::get('marks-info', [ApiController::class, 'marks_info']);



