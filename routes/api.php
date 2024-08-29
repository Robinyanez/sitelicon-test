<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;
use App\Http\Controllers\Api\ProductController;

Route::prefix('orden')->group(function () {

    Route::get('/show/{orderId}', [OrderController::class, 'show']);
    Route::post('/create', [OrderController::class, 'store']);
    Route::post('/confirmation', [PaymentController::class, 'confirmationPayment']);
    /* Route::post('/orden/authorization', [PaymentController::class, 'authorizationPayment']); */
    Route::post('/payment', [PaymentController::class, 'payment']);
});

Route::prefix('product')->group(function () {

    Route::get('/list', [ProductController::class, 'index']);
});




