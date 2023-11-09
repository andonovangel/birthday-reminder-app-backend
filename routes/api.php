<?php

use App\Http\Controllers\Api\{BirthdayController, GroupController, UserController};
use Illuminate\Support\Facades\Route;

Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function() {
    Route::put('/user-update', [UserController::class, 'update']);
    Route::post('/logout', [UserController::class, 'logout']);

    Route::get('/birthdays', [BirthdayController::class, 'index']);
    Route::get('/birthdays/{birthday}', [BirthdayController::class, 'show']);
    Route::get('/birthdays/search/{search}', [BirthdayController::class, 'search']);
    Route::get('/archived-birthdays', [BirthdayController::class, 'archived']);
    Route::post('/birthdays', [BirthdayController::class, 'store']);
    Route::put('/birthdays/{birthday}', [BirthdayController::class, 'update']);
    Route::delete('/birthdays/{birthday}', [BirthdayController::class, 'destroy'])->withTrashed();
    Route::post('/restore-birthday/{birthday}', [BirthdayController::class, 'restore'])->withTrashed();

    Route::get('/groups', [GroupController::class, 'index']);
    Route::get('/groups/{group}', [GroupController::class, 'show']);
    Route::get('/groups/search/{search}', [GroupController::class, 'search']);
    Route::get('/archived-groups', [GroupController::class, 'archived']);
    Route::post('/groups', [GroupController::class, 'store']);
    Route::put('/groups/{group}', [GroupController::class, 'update']);
    Route::delete('/groups/{group}', [GroupController::class, 'destroy'])->withTrashed();
    Route::post('/restore-group/{group}', [GroupController::class, 'restore'])->withTrashed();
});