<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\CustomersController;
use App\Http\Controllers\MerchantController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\MerchantBillsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Vegetable Market Management Routes
    Route::resource('merchants', MerchantController::class);
    Route::resource('customers', CustomerController::class);
    Route::resource('products', ProductsController::class);
    Route::resource('merchant-bills', MerchantBillsController::class);
    Route::resource('customer-bills', \App\Http\Controllers\CustomerBillsController::class)->except(['edit', 'update']);
    Route::resource('customer-payments', \App\Http\Controllers\CustomerPaymentsController::class);
    Route::post('merchant-bills/{merchantBill}/generate-customer-bills', [\App\Http\Controllers\MerchantBillsController::class, 'generateCustomerBills'])->name('merchant-bills.generate-customer-bills');
    Route::get('customer-bills/get-customer-products', [\App\Http\Controllers\CustomerBillsController::class, 'getCustomerProducts'])->name('customer-bills.get-customer-products');
    Route::get('customer-bills/{customerBill}/pdf', [\App\Http\Controllers\CustomerBillsController::class, 'generatePdf'])->name('customer-bills.pdf');
    Route::get('customer-bills/{customerBill}/print', [\App\Http\Controllers\CustomerBillsController::class, 'print'])->name('customer-bills.print');
    Route::get('customer-payments/get-customer-bills', [\App\Http\Controllers\CustomerPaymentsController::class, 'getCustomerBills'])->name('customer-payments.get-customer-bills');
    Route::get('api/customers/{customerId}/balance', [\App\Http\Controllers\CustomerPaymentsController::class, 'getCustomerBalance'])->name('api.customers.balance');

    // Bill edit logs routes
    Route::get('bill-edit-logs', [\App\Http\Controllers\BillEditLogController::class, 'index'])->name('bill-edit-logs.index');
    Route::get('bill-edit-logs/{billEditLog}', [\App\Http\Controllers\BillEditLogController::class, 'show'])->name('bill-edit-logs.show');
    
    // Legacy routes for compatibility
    Route::get('/orders', [OrdersController::class, 'index'])->name('orders.index');
});

require __DIR__.'/auth.php';
