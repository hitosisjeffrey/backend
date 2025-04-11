<?php

use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\BlogController;

Route::middleware('auth:api')->prefix('api')->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']);  // List all blogs
    Route::post('/blogs', [BlogController::class, 'store']);  // Create a new blog
    Route::get('/blogs/{id}', [BlogController::class, 'show']);  // Show a single blog
    Route::put('/blogs/{id}', [BlogController::class, 'update']);  // Update a blog
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);  // Delete a blog
});
