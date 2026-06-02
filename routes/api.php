<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\authController;

Route::middleware('api.key')->group(function(){    
Route::post('/users', [userController::class, 'createUsers']);
Route::get('/users', [userController::class, 'getAllUser']);
Route::get('/users/{id}', [userController::class, 'getUserById']);
Route::put('/users/{id}', [userController::class, 'updateUsers']);
Route::delete('/users/{id}', [userController::class, 'deleteUsers']);
});

Route::post('/register', [authController::class, 'Register']);
Route::post('/login', [authController::class, 'Login']);

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API king huahuha'
    ]);
});