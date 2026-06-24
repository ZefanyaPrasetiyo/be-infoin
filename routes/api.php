<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\userController;
use App\Http\Controllers\authController;
use App\Http\Controllers\categoryController;
use App\Http\Controllers\locationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\commentController;
use App\Http\Controllers\historyController;
use App\Http\Controllers\notificationController;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the API king huahua'
    ]);
})->withoutMiddleware('api.key');

Route::middleware('api.key')->group(function(){    
Route::post('/users', [userController::class, 'createUsers']);
Route::get('/users', [userController::class, 'getAllUser']);
Route::get('/users/{id}', [userController::class, 'getUserById']);
Route::put('/users/{id}', [userController::class, 'updateUsers']);
Route::delete('/users/{id}', [userController::class, 'deleteUsers']);

Route::post('/category', [categoryController::class, 'createCategory']);
Route::get('/category', [categoryController::class, 'getAllCategories']);
Route::get('/category/{id}', [categoryController::class, 'getCategoryById']);
Route::put('/category/{id}', [categoryController::class, 'updateCategory']);
Route::delete('/category/{id}', [categoryController::class, 'deleteCategory']);

Route::get('/reports', [ReportController::class, 'getReport']);
Route::get('/reports/{id}', [ReportController::class, 'getReportById']);
Route::post('/reports', [ReportController::class, 'createReport']);
Route::put('/reports/{id}/status', [ReportController::class, 'updateReportStatus']);
Route::delete('/reports/{id}', [ReportController::class, 'deleteReport']);

Route::get('/comments/{id_report}', [CommentController::class, 'getByReport']);
    Route::post('/comments', [CommentController::class, 'store']);
    Route::put('/comments/{id}', [CommentController::class, 'update']);
    Route::delete('/comments/{id}', [CommentController::class, 'destroy']);

Route::get('/locations', [locationController::class, 'getAllLocations']);
Route::get('/location/{id}', [locationController::class, 'getLocationById']);
Route::post('/location', [locationController::class, 'createLocation']);
Route::put('/location/{id}', [locationController::class, 'updateLocation']);
Route::delete('/location/{id}', [locationController::class, 'deleteLocation']);

Route::get('/locations-deleted', [locationController::class, 'getDeletedLocations']);
Route::put('/location-restore/{id}', [locationController::class, 'restoreDeletedLocation']);

Route::get('/history/{id_user}', [historyController::class, 'getHistory']);
Route::delete('/history/{id}', [historyController::class, 'deleteHistory']);

Route::get('/notifications/{id_user}', [NotificationController::class, 'getNotifications']);
Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
});

Route::post('/register', [authController::class, 'Register']);
Route::post('/login', [authController::class, 'Login']);


