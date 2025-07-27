<?php

use App\Http\Controllers\Api\V1\CustomerControllerV1;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('customers', CustomerControllerV1::class)/* ->middleware('auth:sanctum') */;
