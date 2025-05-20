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
Route::prefix('v1')->group(function () {

    // open routes


    
    // admin routes
    Route::prefix('admin')->group(function () {
        Route::prefix('')->group(function () {
            // there should NOT be admin registration, -  
            // admin should be seeded or stored by an already existing admin -
            // there is a route for admin storing
            Route::post('/login', [AdminAuthController::class, 'login']);

        });






        Route::middleware(['auth:sanctum', 'abilities:access-admin'])->group(function () {

            Route::prefix('')->group(function () {
                Route::post('/logout', [AdminAuthController::class, 'logout']);
                Route::post('/logout-all-devices', [AdminAuthController::class, 'logoutAllDevices']);
            });


            Route::prefix('admins')->group(function () {
                Route::post('/', [AdminController::class, 'store']);
                Route::get('/', [AdminController::class, 'index']);
                Route::prefix('/{admin}')->group(function () {
                    Route::get('/', [AdminController::class, 'show']);
                    Route::put('/', [AdminController::class, 'update']);
                    Route::delete('/', [AdminController::class, 'destroy']);
                }); 
            });

            

            Route::prefix('roles')->group(function () {
                Route::get('/', [RoleController::class, 'index']);
                Route::post('/', [RoleController::class, 'store']);
                Route::prefix('/{role}')->group(function () {
                    Route::get('/', [RoleController::class, 'show']);
                    Route::put('/', [RoleController::class, 'update']);
                    Route::delete('/', [RoleController::class, 'destroy']);
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [RoleController::class, 'restore']);
                });
            });


            Route::prefix('permissions')->group(function () {
                Route::get('/', [PermissionController::class, 'index']);
                Route::post('/', [PermissionController::class, 'store']);
                Route::prefix('/{permission}')->group(function () {
                    Route::get('/', [PermissionController::class, 'show']);
                    Route::put('/', [PermissionController::class, 'update']);
                    Route::delete('/', [PermissionController::class, 'destroy']);
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [PermissionController::class, 'restore']);
                });
            });




        });








    });








    




});