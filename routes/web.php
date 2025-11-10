<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/{short_code}', [App\Http\Controllers\UrlController::class, 'redirect']);