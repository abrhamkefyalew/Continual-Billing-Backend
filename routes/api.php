<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\PermissionController;
use App\Http\Controllers\Api\V1\Auth\AdminAuth\AdminAuthController;


/*
|--------------------------------------------------------------------------
| API Routes   -        -   -   -   -   -   -   -   -   -   -   -   -   -   -   -   - // THIS api.php file was CREATED by Me / ABRHAM    - - - - - - - - - - - -// THIS Code was ADDED BY Me / ABRHAM
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




//
Route::prefix('v1')->name('api.v1.')->group(function () {

    // open routes


    
    // admin routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::prefix('auths')->name('auths.')->group(function () {
            // there should NOT be admin registration, -  
            // admin should be seeded or stored by an already existing admin -
            // there is a route for admin storing
            Route::post('/login', [AdminAuthController::class, 'login'])->name('login');

        });






        Route::middleware(['auth:sanctum', 'abilities:access-admin'])->group(function () {

            Route::prefix('tokens')->name('tokens.')->group(function () {
                Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
                Route::post('/logout-all-devices', [AdminAuthController::class, 'logoutAllDevices'])->name('logoutAllDevices');
            });


            Route::prefix('admins')->name('admins.')->group(function () {
                Route::get('/', [AdminController::class, 'index'])->name('index');
                Route::post('/', [AdminController::class, 'store'])->name('store');
                Route::prefix('/{admin}')->group(function () {
                    Route::get('/', [AdminController::class, 'show'])->name('show');
                    Route::put('/', [AdminController::class, 'update'])->name('update');
                    Route::delete('/', [AdminController::class, 'destroy'])->name('destroy');
                }); 
            });

            

            Route::prefix('roles')->name('roles.')->group(function () {
                Route::get('/', [RoleController::class, 'index'])->name('index');
                Route::post('/', [RoleController::class, 'store'])->name('store');
                Route::prefix('/{role}')->group(function () {
                    Route::get('/', [RoleController::class, 'show'])->name('show');
                    Route::put('/', [RoleController::class, 'update'])->name('update');
                    Route::delete('/', [RoleController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [RoleController::class, 'restore'])->name('restore');
                });
            });


            Route::prefix('permissions')->name('permissions.')->group(function () {
                Route::get('/', [PermissionController::class, 'index'])->name('index');
                Route::post('/', [PermissionController::class, 'store'])->name('store');
                Route::prefix('/{permission}')->group(function () {
                    Route::get('/', [PermissionController::class, 'show'])->name('show');
                    Route::put('/', [PermissionController::class, 'update'])->name('update');
                    Route::delete('/', [PermissionController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [PermissionController::class, 'restore'])->name('restore');
                });
            });




        });








    });








    




});