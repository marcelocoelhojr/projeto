<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\CourseHasUserController;

Route::post('/auth/register', [AuthController::class, 'createUser']);
Route::post('/auth/login', [AuthController::class, 'loginUser']);

Route::group(['middleware' => 'auth:sanctum'], function() {
    Route::get('/users/profile', [UserController::class, 'getUserInfo']);
    Route::put('/users/profile', [UserController::class, 'updateUserInfo']);

    Route::get('/users', [UserController::class, 'getAllUsers'])->middleware('admin');
    Route::get('/users/{id}', [UserController::class, 'getUserById'])->middleware('admin');
    Route::put('/users/{id}', [UserController::class, 'updateUserById'])->middleware('admin');
    
    Route::get('/courses', [CourseController::class, 'index']);
    Route::get('/courses/{id}', [CourseController::class, 'show']);
    Route::post('/courses', [CourseController::class, 'store'])->middleware('admin');
    Route::put('/courses/{id}', [CourseController::class, 'update'])->middleware('admin');
    Route::delete('/courses/{id}', [CourseController::class, 'destroy'])->middleware('admin');

    Route::get('/user/courses', [CourseHasUserController::class, 'userCourses']);
    Route::post('/user/courses', [CourseHasUserController::class, 'store']);
    Route::put('/user/courses/{id}', [CourseHasUserController::class, 'update']);
    Route::delete('/user/courses/{id}', [CourseHasUserController::class, 'destroy']);
    Route::get('/admin/courses', [CourseHasUserController::class, 'index']);
    Route::post('/user/courses/{course}/watched', [CourseHasUserController::class, 'addWatchedClasses']);
    Route::post('/courses/admin/{courseHasUserId}/watched', [CourseHasUserController::class, 'changeWatchedClasses']);
    
});
