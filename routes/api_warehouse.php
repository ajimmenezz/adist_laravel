<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::prefix('Warehouse')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'message' => 'Welcome to the Warehouse API'
        ]);
    });

    Route::get('FixInventoryTroughCensos', [App\Http\Controllers\PA\Warehouse\Fixes::class, 'FixInventoryTroughCensos'])->name('warehouse.fixes.FixInventoryTroughCensos');

    Route::get('Inventory2023/export/{id}', [App\Http\Controllers\Api\Warehouse\Inventory2023::class, 'export'])->name('warehouse.inventory2023.export');
});
