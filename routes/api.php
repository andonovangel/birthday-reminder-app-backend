<?php

use App\Http\Controllers\Api\BirthdayController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::get('/birthdays', [BirthdayController::class, 'index']);
Route::get('/birthdays/{birthday}', [BirthdayController::class, 'show']);
Route::get('/birthdays/search/{search}', [BirthdayController::class, 'search']);
Route::post('/birthdays', [BirthdayController::class, 'store']);
Route::put('/birthdays/{birthday}', [BirthdayController::class, 'edit']);
Route::delete('/birthdays/{birthday}', [BirthdayController::class, 'delete']);
Route::post('/restore-birthday/{birthday}', [BirthdayController::class, 'restore'])->withTrashed();
Route::get('/archived-birthdays', [BirthdayController::class, 'archived']);