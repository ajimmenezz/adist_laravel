<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Logistic\Pickup;
use App\Http\Controllers\Support\BranchInventory;
use App\Http\Controllers\Warehouse\Distribution;
use App\Http\Middleware\ValidateAdISTToken;
use App\Http\Controllers\Logistic\Distribution as LogisticDistribution;
use App\Http\Controllers\Warehouse\Inventory2023;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::middleware([ValidateAdISTToken::class])->group(function () {
    Route::get('/', [HomeController::class, 'index'])->name('home');

    Route::prefix('Almacen')->group(function () {
        Route::get('Distribucion', [Distribution::class, 'index'])->name('warehouse.distribution.index');
        Route::get('Distribucion/{id}', [Distribution::class, 'one'])->name('warehouse.distribution.one');
        Route::get('Inventario-2023', [Inventory2023::class, 'index'])->name('warehouse.inventory2023.index');
    });

    Route::prefix('Logistica')->group(function () {
        Route::get('Recoleccion', [Pickup::class, 'index'])->name('logistic.pickup.index');
        Route::get('Recoleccion/{id}', [Pickup::class, 'one'])->name('logistic.pickup.one');

        Route::get('Distribucion', [LogisticDistribution::class, 'index'])->name('logistic.distribution.index');
    });

    Route::prefix('Soporte-en-sitio')->group(function () {
        Route::get('Censos', [BranchInventory::class, 'index'])->name('support.branch_inventory.index');
        Route::get('Censos/{id}', [BranchInventory::class, 'one'])->name('support.branch_inventory.one');
        Route::get('Censos/sheetExport/{id}', [BranchInventory::class, 'xlsExport'])->name('support.branch_inventory.export');

        Route::get('Censos/{id}/{area}', [BranchInventory::class, 'area'])->name('support.branch_inventory.area');
    });
});

Route::get('/logout', function () {
    Auth::logout();
    session()->flush();
    return redirect('/');
});


Auth::routes();
