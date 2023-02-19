<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Logistic\Pickup;
use App\Http\Controllers\Api\Devices\Components;
use App\Http\Controllers\Api\Support\BranchInventory;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {
    Route::get('Logistic/Pickup', [Pickup::class, 'index']);
    Route::put('Logistic/Pickup', [Pickup::class, 'store']);
    Route::put('Logistic/Pickup/{id}/BoxedCensoItems', [Pickup::class, 'storeBoxedCensoItems']);
    Route::delete('Logistic/Pickup/{id}/BoxedCensoItems', [Pickup::class, 'deleteBoxedCensoItems']);
    Route::put('Logistic/Pickup/{id}/Items', [Pickup::class, 'storeExtraItems']);
    Route::delete('Logistic/Pickup/{id}/Items/{rid}', [Pickup::class, 'deleteExtraItem']);
    Route::get('Logistic/Pickup/{id}/Pdf', [Pickup::class, 'exportPdf']);

    Route::get('Devices/{id}/Components', [Components::class, 'getComponentsByModelId']);

    Route::prefix('Support')->group(function () {
        Route::prefix('Branch-Inventory')->group(function () {
            Route::get('/', [BranchInventory::class, 'index']);
        });
    });
});
