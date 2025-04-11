<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use \App\Http\Controllers\BlogController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::middleware('auth:api')->group(function () {
    Route::get('/blogs', [BlogController::class, 'index']);  
    Route::post('/blogs', [BlogController::class, 'store']);  
    Route::get('/blogs/{id}', [BlogController::class, 'show']);  
    Route::put('/blogs/{id}', [BlogController::class, 'update']); 
    Route::patch('/blogs/status/{id}', [BlogController::class, 'changeStatus']); 
    Route::delete('/blogs/{id}', [BlogController::class, 'destroy']);  // Delete a blog
});
