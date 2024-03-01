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
    Route::get('/birthdays/{id}', [BirthdayController::class, 'show']);
    Route::get('/archived-birthdays', [BirthdayController::class, 'archived']);
    Route::post('/birthdays', [BirthdayController::class, 'store']);
    Route::put('/birthdays/{id}', [BirthdayController::class, 'update']);
    Route::delete('/birthdays/{id}', [BirthdayController::class, 'destroy']);
    Route::post('/restore-birthday/{id}', [BirthdayController::class, 'restore']);
    Route::post('/send-email', [BirthdayController::class, 'send']);

    // Groups
    Route::get('/groups', [GroupController::class, 'index']);
    Route::get('/groups/{id}', [GroupController::class, 'show']);
    Route::get('/groups/{id}/birthdays', [GroupController::class, 'list']);
    Route::get('/groups/search/{search}', [GroupController::class, 'search']);
    Route::get('/archived-groups', [GroupController::class, 'archived']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::put('/groups/{id}', [GroupController::class, 'update']);
    Route::delete('/groups/{id}', [GroupController::class, 'destroy']);
    Route::post('/restore-group/{id}', [GroupController::class, 'restore']);
});