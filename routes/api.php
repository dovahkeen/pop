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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

//Route::post('login', '')

Route::resources([
    'teacher' => TeacherController::class,
    'student' => StudentController::class,
    'period'  => PeriodController::class
]);

Route::get('period/teacher/{teacherId}', [PeriodController::class, 'getByTeacher']);
Route::get('student/period/{periodId}', [StudentController::class, 'index']);
Route::get('student/period/{periodId}/teacher/{teacherId}', [StudentController::class, 'index']);

Route::post('teacher/login', [TeacherController::class, 'login']);
Route::post('student/login', [StudentController::class, 'login']);
