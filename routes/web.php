<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\MemberController;
use App\Http\Controllers\ItemController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Home page
Route::get('/', function () {
    return view('welcome');
});

// Sale routes
Route::resource('sales', SaleController::class);

// Payment routes (assuming nested under sales)
Route::resource('sales.payments', PaymentController::class)->shallow();

// Member routes
Route::resource('members', MemberController::class);

// Item routes
Route::resource('items', ItemController::class);

