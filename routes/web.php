<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\GroupController;
use App\Http\Controllers\BirthdayController;
use App\Http\Controllers\MailController;

Route::post('/register', [UserController::class, 'register']);
Route::post('/logout', [UserController::class, 'logout']);
Route::post('/login', [UserController::class, 'login']);

Route::get('/', [BirthdayController::class, 'index']);
Route::post('/create-birthday', [BirthdayController::class, 'createBirthday']);
Route::get('/edit-birthday/{birthday}', [BirthdayController::class, 'showEditBirthday']);
Route::put('/edit-birthday/{birthday}', [BirthdayController::class, 'editBirthday']);
Route::get('/archived-brithdays', [BirthdayController::class, 'archivedBirthdays']);
Route::delete('/delete-birthday/{birthday}', [BirthdayController::class, 'deleteBirthday'])->withTrashed();
Route::post('/restore-birthday/{birthday}', [BirthdayController::class, 'restoreBirthday'])->withTrashed();
Route::get('/list-birthdays', [BirthdayController::class, 'listBirthdays'])->name('list-birthdays');

Route::get('/groups', [GroupController::class, 'index']);
Route::post('/create-group', [GroupController::class, 'createGroup']);
Route::get('/edit-group/{group}', [GroupController::class, 'showEditGroup']);
Route::put('/edit-group/{group}', [GroupController::class, 'editGroup']);
Route::get('/remove-from-group/{birthday}', [GroupController::class, 'removeFromGroup']);
Route::get('/groups/archived-groups', [GroupController::class, 'archivedGroups']);
Route::delete('/delete-group/{group}', [GroupController::class, 'deleteGroup'])->withTrashed();
Route::post('restore-group/{group}', [GroupController::class, 'restoreGroup'])->withTrashed();

Route::get('/send-email', [MailController::class, 'sendMail']);