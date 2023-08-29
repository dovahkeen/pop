<?php

use App\Http\Controllers\PeriodController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherController;
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

Route::post('teacher', [TeacherController::class, 'store']);
Route::post('student', [StudentController::class, 'store']);

Route::post('teacher/login', [TeacherController::class, 'login']);
Route::post('student/login', [StudentController::class, 'login']);

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::middleware('auth:sanctum')->group(function(){

    Route::resource('teacher', TeacherController::class)->except(['store']);
    Route::resource('student', StudentController::class)->except(['store']);
    Route::resource('period', PeriodController::class);

    Route::get('period/teacher/{teacherId}', [PeriodController::class, 'getByTeacher']);
    Route::get('student/period/{periodId}', [StudentController::class, 'index']);
    Route::get('student/period/{periodId}/teacher/{teacherId}', [StudentController::class, 'index']);

});
