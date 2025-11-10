<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UrlController;
use Illuminate\Support\Facades\App;


Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// Auth Routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

//URL Controller Routes
Route::middleware('auth:sanctum')->group(function() {

    Route::get('/url', [UrlController::class, 'index']);
    Route::get('/url/{id}', [UrlController::class, 'show']);
    Route::delete('/url/{id}', [UrlController::class, 'destroy']);
    Route::post('/shorten', [UrlController::class,  'shorten']);
});

