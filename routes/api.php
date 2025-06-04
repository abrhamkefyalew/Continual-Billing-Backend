<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\Admin\RoleController;
use App\Http\Controllers\Api\V1\Admin\AdminController;
use App\Http\Controllers\Api\V1\Admin\AssetMainController;
use App\Http\Controllers\Api\V1\Admin\AssetPoolController;
use App\Http\Controllers\Api\V1\Admin\AssetUnitController;
use App\Http\Controllers\Api\V1\Admin\AuditTrailController;
use App\Http\Controllers\Api\V1\Admin\DirectiveController;
use App\Http\Controllers\Api\V1\Admin\EnterpriseController;
use App\Http\Controllers\Api\V1\Admin\PermissionController;
use App\Http\Controllers\Api\V1\Admin\EnterpriseUserController;
use App\Http\Controllers\Api\V1\Admin\InvoicePoolController;
use App\Http\Controllers\Api\V1\Admin\InvoiceUnitController;
use App\Http\Controllers\Api\V1\Admin\PayerController;
use App\Http\Controllers\Api\V1\Admin\PenaltyController;
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



Route::get('check-ip', function () {
    $message = "Check IP: - request came from ip (IP from request): - \n - using DEFAULT IP CHECKER (From Laravel Helper function) = " . request()->ip() . " ,   \n - using CUSTOM IP CHECKER (From New function) = " . \App\Services\AppService::getIp();

    \Illuminate\Support\Facades\Log::info($message);

    return $message;
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







            // // // 
            //


            Route::prefix('enterprises')->name('enterprises.')->group(function () {
                Route::get('/', [EnterpriseController::class, 'index'])->name('index');
                Route::post('/', [EnterpriseController::class, 'store'])->name('store');
                Route::prefix('/{enterprise}')->group(function () {
                    Route::get('/', [EnterpriseController::class, 'show'])->name('show');
                    Route::put('/', [EnterpriseController::class, 'update'])->name('update');
                    Route::delete('/', [EnterpriseController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [EnterpriseController::class, 'restore'])->name('restore');
                });
            });

            Route::prefix('enterprise_users')->name('enterprise_users.')->group(function () {
                Route::get('/', [EnterpriseUserController::class, 'index'])->name('index');
                Route::post('/', [EnterpriseUserController::class, 'store'])->name('store');
                Route::prefix('/{enterpriseUser}')->group(function () {
                    Route::get('/', [EnterpriseUserController::class, 'show'])->name('show');
                    Route::put('/', [EnterpriseUserController::class, 'update'])->name('update');
                    Route::delete('/', [EnterpriseUserController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [EnterpriseUserController::class, 'restore'])->name('restore');
                });
            });




            Route::prefix('payers')->name('payers.')->group(function () {
                Route::get('/', [PayerController::class, 'index'])->name('index');
                Route::post('/', [PayerController::class, 'store'])->name('store');
                Route::prefix('/{payer}')->group(function () {
                    Route::get('/', [PayerController::class, 'show'])->name('show');
                    Route::put('/', [PayerController::class, 'update'])->name('update');
                    Route::delete('/', [PayerController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [PayerController::class, 'restore'])->name('restore');
                });
            });






            Route::prefix('directives')->name('directives.')->group(function () {
                Route::get('/', [DirectiveController::class, 'index'])->name('index');
                Route::post('/', [DirectiveController::class, 'store'])->name('store');
                Route::prefix('/{directive}')->group(function () {
                    Route::get('/', [DirectiveController::class, 'show'])->name('show');
                    Route::put('/', [DirectiveController::class, 'update'])->name('update');
                    Route::delete('/', [DirectiveController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [DirectiveController::class, 'restore'])->name('restore');
                });
            });
            
            Route::prefix('penalties')->name('penalties.')->group(function () {
                Route::get('/', [PenaltyController::class, 'index'])->name('index');
                Route::post('/', [PenaltyController::class, 'store'])->name('store');
                Route::prefix('/{penalty}')->group(function () {
                    Route::get('/', [PenaltyController::class, 'show'])->name('show');
                    Route::put('/', [PenaltyController::class, 'update'])->name('update');
                    Route::delete('/', [PenaltyController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [PenaltyController::class, 'restore'])->name('restore');
                });
            });






            Route::prefix('asset_mains')->name('asset_mains.')->group(function () {
                Route::get('/', [AssetMainController::class, 'index'])->name('index');
                Route::post('/', [AssetMainController::class, 'store'])->name('store');
                Route::prefix('/{assetMain}')->group(function () {
                    Route::get('/', [AssetMainController::class, 'show'])->name('show');
                    Route::put('/', [AssetMainController::class, 'update'])->name('update');
                    Route::delete('/', [AssetMainController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [AssetMainController::class, 'restore'])->name('restore');
                });
            });


            

            Route::prefix('asset_units')->name('asset_units.')->group(function () {
                Route::get('/', [AssetUnitController::class, 'index'])->name('index');
                Route::post('/', [AssetUnitController::class, 'store'])->name('store');
                Route::prefix('/{assetUnit}')->group(function () {
                    Route::get('/', [AssetUnitController::class, 'show'])->name('show');
                    Route::put('/', [AssetUnitController::class, 'update'])->name('update');
                    Route::delete('/', [AssetUnitController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [AssetUnitController::class, 'restore'])->name('restore');
                });
            });

            Route::prefix('invoice_units')->name('invoice_units.')->group(function () {
                Route::get('/', [InvoiceUnitController::class, 'index'])->name('index');
                Route::post('/', [InvoiceUnitController::class, 'store'])->name('store');
                Route::prefix('/{invoiceUnit}')->group(function () {
                    Route::get('/', [InvoiceUnitController::class, 'show'])->name('show');
                    Route::put('/', [InvoiceUnitController::class, 'update'])->name('update');
                    Route::delete('/', [InvoiceUnitController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [InvoiceUnitController::class, 'restore'])->name('restore');
                });
            });




            Route::prefix('asset_pools')->name('asset_pools.')->group(function () {
                Route::get('/', [AssetPoolController::class, 'index'])->name('index');
                Route::post('/', [AssetPoolController::class, 'store'])->name('store');
                Route::prefix('/{assetPool}')->group(function () {
                    Route::get('/', [AssetPoolController::class, 'show'])->name('show');
                    Route::put('/', [AssetPoolController::class, 'update'])->name('update');
                    Route::delete('/', [AssetPoolController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [AssetPoolController::class, 'restore'])->name('restore');
                });
            });

            Route::prefix('invoice_pools')->name('invoice_pools.')->group(function () {
                Route::get('/', [InvoicePoolController::class, 'index'])->name('index');
                Route::post('/', [InvoicePoolController::class, 'store'])->name('store');
                Route::prefix('/{invoicePool}')->group(function () {
                    Route::get('/', [InvoicePoolController::class, 'show'])->name('show');
                    Route::put('/', [InvoicePoolController::class, 'update'])->name('update');
                    Route::delete('/', [InvoicePoolController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [InvoicePoolController::class, 'restore'])->name('restore');
                });
            });







            Route::prefix('audit_trails')->name('audit_trails.')->group(function () {
                Route::get('/', [AuditTrailController::class, 'index'])->name('index');
                Route::post('/', [AuditTrailController::class, 'store'])->name('store');
                Route::prefix('/{auditTrail}')->group(function () {
                    Route::get('/', [AuditTrailController::class, 'show'])->name('show');
                    Route::put('/', [AuditTrailController::class, 'update'])->name('update');
                    Route::delete('/', [AuditTrailController::class, 'destroy'])->name('destroy');
                });
                Route::prefix('/{id}')->group(function () {
                    Route::post('/restore', [AuditTrailController::class, 'restore'])->name('restore');
                });
            });

            


        });








    });








    




});