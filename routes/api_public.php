<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Welcome to the Public API'
    ]);
});

require __DIR__ . '/api_warehouse.php';
