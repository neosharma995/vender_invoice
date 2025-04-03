<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendorController;
use App\Http\Controllers\QuotationController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ItemController;
Route::get('/clear-cache', function () {
    Artisan::call('optimize:clear');
    return "Cache cleared successfully!";
})->name('clear.cache');

require __DIR__.'/auth.php';

// Root (/) route: redirects users based on authentication status
Route::get('/', function () {
    return auth()->check() ? redirect('/dashboard') : redirect('/login');
});

// Dashboard route with AdminLTE view
Route::middleware(['auth'])->group(function () {
    Route::resource('users', UserController::class);
    Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('invoices', InvoiceController::class);        
    Route::get('/invoice/{id}/pdf', [InvoiceController::class, 'generatePdf'])->name('generatePdf');
    Route::resource('vendors', VendorController::class);
    Route::resource('quotations', QuotationController::class);
    Route::post('/quotations/{id}/clone', [QuotationController::class, 'clone'])->name('quotations.clone');
    Route::get('/quotations/{id}/print', [QuotationController::class, 'print'])->name('quotations.print');

    Route::resource('customers', CustomerController::class);
    Route::resource('items', ItemController::class);


});
