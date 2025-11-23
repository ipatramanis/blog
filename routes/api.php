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
    Route::get('/posts/{post_id}/slug/{slug}', [PostController::class, 'get']);
    Route::get('/posts', [PostController::class, 'getList']);
    Route::post('/posts', [PostController::class, 'create']);
    Route::put('/posts/{post}', [PostController::class, 'update']);
    Route::patch('/posts/{post}', [PostController::class, 'update']);
    Route::delete('/posts/{post}', [PostController::class, 'delete']);
    Route::get('/posts/author/{user_id}', [PostController::class, 'getListByUser']);
});
