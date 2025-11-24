<?php

use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Preview comment mail notification
Route::get('/comments/{comment_id}/notifications/preview', [CommentController::class, 'previewNotification']);
