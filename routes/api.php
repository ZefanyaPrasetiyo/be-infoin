<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\authController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::post('/register', [authController::class, 'Register']);
Route::post('/login', [authController::class, 'Login']);

Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request){
    $request->fulfill();
    return response()->json(['message'=>'email berhasil diverifikasi?']);
})->middleware(['signed'])->name('verification.verify');


Route::middleware('auth:api')->group(function(){    
Route::post('/users', [userController::class, 'createUsers']);
Route::get('/users', [userController::class, 'getAllUser']);
Route::get('/users/{id}', [userController::class, 'getUserById']);
Route::put('/users/{id}', [userController::class, 'updateUsers']);
Route::delete('/users/{id}', [userController::class, 'deleteUsers']);

    });