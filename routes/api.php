<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\CheckApiClientToken;
use App\Http\Controllers\Api\V1\CustomerControllerV1;




Route::middleware(CheckApiClientToken::class)->group(function () {
    Route::get('customers/document/{document}', [CustomerControllerV1::class, 'showByDocument']);
});

/* Route::middleware(CheckApiClientToken::class)->group(function () {
    Route::get('customers', [CustomerControllerV1::class, 'index']);
}); */
