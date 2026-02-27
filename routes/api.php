<?php

use App\Http\Controllers\Auth\ForgetPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Auth\VerifyOtpController;
use App\Http\Controllers\Course\CourseController;
use App\Http\Controllers\Instructor\AssignmentController;
use App\Http\Controllers\Instructor\CourseCertificateController;
use App\Http\Controllers\Instructor\LectureController;
use App\Http\Controllers\Instructor\QuizController;
use App\Http\Controllers\User\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('auth')->group(function () {

    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [RegisterController::class, 'register']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LogoutController::class, 'logout']);
    });

});

Route::prefix('auth')->group(function () {

    Route::post('/forget-password', [ForgetPasswordController::class, 'sendOtp']);
    Route::post('/verify-otp', [VerifyOtpController::class, 'verify']);
    Route::post('/reset-password', [ResetPasswordController::class, 'reset']);

});
Route::middleware('auth:sanctum')->group(function () {

    Route::get('/profile', [UserController::class, 'profile']);
    Route::put('/update', [UserController::class, 'update']);
    Route::put('/change-password', [UserController::class, 'changePassword']);
    Route::delete('/delete-account', [UserController::class, 'deleteAccount']);
});

// Public Routes
Route::get('/courses', [CourseController::class, 'index']);
Route::get('/courses/{course}', [CourseController::class, 'show']);

// Protected Routes (Instructor Only)
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/courses', [CourseController::class, 'store']);
    Route::put('/courses/{course}', [CourseController::class, 'update']);
    Route::delete('/courses/{course}', [CourseController::class, 'destroy']);
    Route::get('/my-courses', [CourseController::class, 'myCourses']);

});

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/courses/{course}/lectures', [LectureController::class, 'store']);
    Route::post('/lectures/{lecture}/video', [LectureController::class, 'uploadVideo']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/lectures/{lecture}/assignments', [AssignmentController::class, 'store']);

});
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/courses/{course}/certificate', [CourseCertificateController::class, 'store']);
});

Route::middleware('auth:sanctum')->group(function () {

    Route::post('/courses/{course}/quizzes', [QuizController::class, 'store']);
    Route::post('/quizzes/{quiz}/questions', [QuizController::class, 'addQuestion']);
    Route::post('/quizzes/{quiz}/publish', [QuizController::class, 'publish']);
});
