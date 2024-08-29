<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PaymentController;

Route::get('/orden/show/{orderId}', [OrderController::class, 'show']);
Route::post('/orden/create', [OrderController::class, 'store']);
Route::post('/orden/confirmation', [PaymentController::class, 'confirmationPayment']);
/* Route::post('/orden/authorization', [PaymentController::class, 'authorizationPayment']); */
Route::post('/orden/payment', [PaymentController::class, 'payment']);
