<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\PermissionController;
use App\Http\Controllers\Api\V1\Auth\AdminAuth\AdminAuthController;


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Web Routes (default 'web' middleware)
|
| These routes use the 'web' middleware group, which includes session state,
| CSRF protection, cookies, etc.
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/about', function () {
    return 'This is the about page (web route)';
});




Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});











/*
|--------------------------------------------------------------------------
| API-like Routes (in web.php, using 'api' middleware manually)
|--------------------------------------------------------------------------
|
| These routes mimic the behavior of routes/api.php, without registering it.
| They use the 'api' middleware group for stateless behavior (e.g., throttle,
| JSON responses, route model binding).
|
|
| Middleware: 'api' (stateless, throttle, bindings, etc.)
| //
|--------------------------------------------------------------------------
| API Routes (manual registration in web.php)
| - Outer prefix: 'api'
| - Inner prefix: 'v1'
|
|
| - Middleware: 'api' applied at the top
|--------------------------------------------------------------------------
|
|
*/
//
// API Routes Grouped
/*
Route::middleware('api')->prefix('api')->group(function () {

    Route::prefix('v1')->group(function () {


        // open routes
        Route::get('/test-route', function () {
            return ['test one', 'test two'];
        });



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







});

*/

