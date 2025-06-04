<?php

use App\Http\Controllers\CompleteLessonController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\DifficultyLevelController;
use App\Http\Controllers\EnrollController;
use App\Http\Controllers\LessonCommentController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\UserCoursesController;
use Illuminate\Support\Facades\Route;


Route::prefix('v1/courses')->group(function () {
    Route::resource('tags', TagController::class);
    Route::resource('/statuses', StatusController::class);
    Route::resource('/difficulty-levels', DifficultyLevelController::class);

});

Route::resource('v1/modules', ModuleController::class);
Route::resource('v1/courses', CourseController::class);
Route::resource('v1/lessons', LessonController::class);
Route::post('v1/courses/{course}/enroll', [EnrollController::class, 'enroll']);
Route::get('v1/courses/{course}/enrollment-status', [EnrollController::class, 'enrollStatus']);
Route::get('v1/user-courses', UserCoursesController::class);


Route::get('v1/lessons/{lesson}/comments', [LessonCommentController::class, 'index']);
Route::post('v1/lessons/{lesson}/comments', [LessonCommentController::class, 'store']);

Route::patch('v1/lessons/{lesson}/complete', CompleteLessonController::class);


