<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/users', [userController::class, 'getAllUser']);
Route::get('/users/{id}', [userController::class, 'getUserById']);
Route::post('/users', [userController::class, 'createUsers']);
Route::put('/users/{id}', [userController::class, 'updateUsers']);
Route::delete('/users/{id}', [userController::class, 'deleteUsers']);
