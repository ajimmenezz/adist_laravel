<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the Public API'
    ]);
});


Route::prefix('permissions')->group(function () {
    Route::get('/', function () {
        return response()->json([
            'permissions' => Permission::all()
        ]);
    });

    Route::put('/new', function (Request $request) {
        $permission = Permission::create([
            'name' => $request->input('name'),
            'guard_name' => 'api'
        ]);

        return response()->json([
            'permission' => $permission
        ]);
    });
});


Route::get('/permissions', function () {
    return response()->json([
        'permissions' => Permission::all()
    ]);
});

require __DIR__ . '/api_warehouse.php';
