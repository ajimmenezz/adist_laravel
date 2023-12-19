<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Logistic\Pickup;
use App\Http\Controllers\Api\Devices\Components;
use App\Http\Controllers\Api\Logistic\Distribution\Destinations;
use App\Http\Controllers\Api\Support\BranchInventory;
use App\Http\Controllers\Api\Warehouse\Distribution;
use App\Http\Controllers\Api\Warehouse\DistributionDevices;
use App\Http\Controllers\Api\Warehouse\Inventory2023;
use Illuminate\Support\Facades\App;
use App\Http\Controllers\Outsourcing\Reports;

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
            Route::put("/{id}/{area}", [BranchInventory::class, 'storePoint']);

            Route::put("/{id}/{area}/{point}/Device", [BranchInventory::class, 'storeDevice']);

            Route::delete("Device/{id}", [BranchInventory::class, 'deleteDevice']);

            Route::post("/UpdateModel/{id}", [BranchInventory::class, 'updateModel']);
            Route::post("/UpdateSerial/{id}", [BranchInventory::class, 'updateSerial']);
            Route::post("/UpdateStatus/{id}", [BranchInventory::class, 'updateStatus']);
            Route::post("/UpdateFeatureValue/{id}", [BranchInventory::class, 'updateFeature']);
            Route::post("/UpdateAccesory/{id}", [BranchInventory::class, 'updateAccesory']);
        });
    });
});

Route::prefix('v2')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to the API v2'
        ]);
    });

    Route::get('/Outsourcing/Reports/WeekPendingInvoices', [Reports::class, 'weekPendingInvoices']);
});

Route::prefix('v3')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to the API v3'
        ]);
    });

    Route::prefix('Warehouse')->group(function () {

        Route::get('/Distribution', [Distribution::class, 'index']);
        Route::post('/Distribution', [Distribution::class, 'store']);

        Route::get('/Distribution/{id}/Devices', [DistributionDevices::class, 'index']);
        Route::put('/Distribution/{id}/Devices', [DistributionDevices::class, 'store']);

        Route::get('/Distribution/AvailableInventory/{customerId}', [Distribution::class, 'availableInventory'])->name('warehouse.distribution.available_inventory');

        Route::delete('Distribution/{id}', [DistributionDevices::class, 'destroy']);

        Route::post('Destination/ToLogistic/{id}', [DistributionDevices::class, 'toLogistic']);
        Route::delete('Destination/ToLogistic/{id}', [DistributionDevices::class, 'cancelToLogistic']);

        Route::post('Destination/ToSupport/{id}', [DistributionDevices::class, 'toSupport']);
        Route::delete('Destination/ToSupport/{id}', [DistributionDevices::class, 'cancelToSupport']);

        Route::post('Distribution/TransferCode', [DistributionDevices::class, 'transferCode']);

        Route::get('Inventory2023/{id}', [Inventory2023::class, 'index']);
        Route::post('Inventory2023/{id}', [Inventory2023::class, 'update']);
        Route::get('Inventory2023/export/{id}', [Inventory2023::class, 'export']);
    });

    Route::prefix('Logistic')->group(function () {
        Route::get('/Destinations', [Destinations::class, 'index']);

        Route::get('/Distribution/Destination/Devices', [DistributionDevices::class, 'pendingTransferDevices']);

        Route::post('/Distribution/Destination/AcceptDevices', [DistributionDevices::class, 'acceptDevices']);
    });
});
