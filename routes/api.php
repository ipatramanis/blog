<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Authentication routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    // Authentication routes
    Route::post('/logout', [AuthController::class, 'logout']);

    // Users routes
    Route::resource('users', UserController::class);

    // Posts routes
    Route::post('/posts/{id}', [PostController::class, 'get']);
    Route::post('/posts', [PostController::class, 'create']);
    Route::post('/posts/{id}', [PostController::class, 'update']);
    Route::get('/posts/author/{author}', [PostController::class, 'getListByUser']);
});
