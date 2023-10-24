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
});
