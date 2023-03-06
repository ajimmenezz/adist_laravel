<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Logistic\Pickup;
use App\Http\Controllers\Support\BranchInventory;
use App\Http\Middleware\ValidateAdISTToken;

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

    Route::prefix('Logistica')->group(function () {
        Route::get('Recoleccion', [Pickup::class, 'index'])->name('logistic.pickup.index');
        Route::get('Recoleccion/{id}', [Pickup::class, 'one'])->name('logistic.pickup.one');
    });

    Route::prefix('Soporte-en-sitio')->group(function () {
        Route::get('Censos', [BranchInventory::class, 'index'])->name('support.branch_inventory.index');
        Route::get('Censos/{id}', [BranchInventory::class, 'one'])->name('support.branch_inventory.one');
        Route::get('Censos/{id}/{area}', [BranchInventory::class, 'area'])->name('support.branch_inventory.area');
    });
});

Route::get('/logout', function () {
    Auth::logout();
    session()->flush();
    return redirect('/');
});

Auth::routes();
