<?php

use App\Http\Controllers\Api\BirthdayController;
use App\Http\Controllers\Api\GroupController;
use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/birthdays', [BirthdayController::class, 'index']);
    Route::get('/birthdays/{birthday}', [BirthdayController::class, 'show']);
    Route::get('/birthdays/search/{search}', [BirthdayController::class, 'search']);
    Route::get('/archived-birthdays', [BirthdayController::class, 'archived']);
    Route::post('/birthdays', [BirthdayController::class, 'store']);
    Route::put('/birthdays/{birthday}', [BirthdayController::class, 'edit']);
    Route::delete('/birthdays/{birthday}', [BirthdayController::class, 'delete']);
    Route::post('/restore-birthday/{birthday}', [BirthdayController::class, 'restore']);

    Route::get('/groups', [GroupController::class, 'index']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::get('/groups/search/{search}', [GroupController::class, 'search']);
    Route::get('/archived-groups', [GroupController::class, 'archived']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::put('/groups/{group}', [GroupController::class, 'edit']);
    Route::delete('/groups/{group}', [GroupController::class, 'delete']);
    Route::post('/restore-group/{group}', [GroupController::class, 'restore']);
});