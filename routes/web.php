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


    Route::prefix('v1')->group(function () {

        Route::prefix('admin')->group(function () {





            
            // Route::prefix('admins')->group(function () {
            //     Route::post('/', [App\Http\Controllers\AdminController::class, 'store']);
            //     Route::get('/', [App\Http\Controllers\AdminController::class, 'index']);
            //     Route::prefix('/{admin}')->group(function () {
            //         Route::get('/', [App\Http\Controllers\AdminController::class, 'show']);
            //         Route::put('/', [App\Http\Controllers\AdminController::class, 'update']);
            //         Route::delete('/', [App\Http\Controllers\AdminController::class, 'destroy']);
            //     }); 
            // });








        });

    });

});
