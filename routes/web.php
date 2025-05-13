<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});













// API routes (grouped)
Route::prefix('api')->group(function () {
    Route::get('/users', function () {
        return ['John Doe', 'Jane Doe'];
    });
});
