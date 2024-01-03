<?php

use App\Http\Controllers\Api\{AdminController, AuthController, BirthdayController, GroupController, UserController};
use Illuminate\Support\Facades\Route;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password-reset', [AuthController::class, 'sendResetPasswordEmail']);
Route::post('/password-reset/{token}', [AuthController::class, 'resetPassword']);

Route::group(['middleware' => ['auth:sanctum', 'checkToken']], function() {
    // User & Authentication
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [UserController::class, 'show']);
    Route::put('/user', [UserController::class, 'update']);
    Route::post('/password-change', [AuthController::class, 'changePassword']);

    // Admin
    Route::get('/admin/users', [AdminController::class, 'index'])->middleware('admin');
    Route::put('/admin/users/{userId}', [AdminController::class, 'update'])->middleware('admin');

    // Birthdays
    Route::get('/birthdays', [BirthdayController::class, 'index']);
    Route::get('/birthdays/{birthday}', [BirthdayController::class, 'show']);
    Route::get('/birthdays/search/{search}', [BirthdayController::class, 'search']);
    Route::get('/archived-birthdays', [BirthdayController::class, 'archived']);
    Route::post('/birthdays', [BirthdayController::class, 'store']);
    Route::put('/birthdays/{birthday}', [BirthdayController::class, 'update']);
    Route::delete('/birthdays/{birthday}', [BirthdayController::class, 'destroy'])->withTrashed();
    Route::post('/restore-birthday/{birthday}', [BirthdayController::class, 'restore'])->withTrashed();

    // Groups
    Route::get('/groups', [GroupController::class, 'index']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::get('/groups/search/{search}', [GroupController::class, 'search']);
    Route::get('/archived-groups', [GroupController::class, 'archived']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::put('/groups/{group}', [GroupController::class, 'update']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->withTrashed();
    Route::post('/restore-group/{group}', [GroupController::class, 'restore'])->withTrashed();
});