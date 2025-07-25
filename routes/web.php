<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'generateInvoiceFile'])->name('checkout');