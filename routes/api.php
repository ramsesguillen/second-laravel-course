<?php

use App\Http\Controllers\AmbassadorController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StatsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

function common($scope)
{
    route::post('register', [AuthController::class, 'register']);
    route::post('login', [AuthController::class, 'login']);

    Route::middleware('auth:sanctum', $scope)->group(function() {
        Route::get('user', [AuthController::class, 'user']);
        Route::post('logout', [AuthController::class, 'logout']);
        Route::post('user/info', [AuthController::class, 'updateInfo']);
        Route::post('user/password', [AuthController::class, 'updatePassword']);
    });
}

// Admin
Route::prefix('admin')->group(function () {
    common('scope.admin');

    Route::middleware('auth:sanctum', 'scope.admin')->group(function() {
        Route::get('ambassador', [AmbassadorController::class, 'index']);
        Route::apiResource('products', ProductController::class);
        Route::get('users/{id}/links', [LinkController::class, 'index']);
        Route::get('orders', [OrderController::class, 'index']);
    });
});

// Ambassador
Route::prefix('ambassador')->group(function () {
    common('scope.ambassador');
    Route::middleware('auth:sanctum', 'scope.ambassador')->group(function() {
        Route::get('products/fronted', [ProductController::class, 'frontend']);
        Route::get('products/backend', [ProductController::class, 'backend']);
        Route::get('stats', [StatsController::class, 'index']);
        Route::get('rankings', [StatsController::class, 'rankings']);
        Route::post('links', [LinkController::class, 'store']);
    });
});

// Ambassador
Route::prefix('checkout')->group(function () {
    Route::post('links/{code}', [LinkController::class, 'show']);
    // common('scope.ambassador');
    // Route::middleware('auth:sanctum', 'scope.ambassador')->group(function() {
    //     Route::get('products/fronted', [ProductController::class, 'frontend']);
    //     Route::get('products/backend', [ProductController::class, 'backend']);
    //     Route::get('stats', [StatsController::class, 'index']);
    //     Route::get('rankings', [StatsController::class, 'rankings']);
    //     Route::post('links', [LinkController::class, 'store']);
    // });
});

