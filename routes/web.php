<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');

// Invoices Routes
Route::prefix('invoices')->name('invoices.')->middleware('auth')->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index');
    Route::get('/create', [InvoiceController::class, 'create'])->name('create');
    Route::post('/', [InvoiceController::class, 'store'])->name('store');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('show');
    Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [InvoiceController::class, 'update'])->name('update');
    Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy');

    // Invoice actions
    Route::post('/{id}/send', [InvoiceController::class, 'send'])->name('send');
    Route::post('/{id}/mark-sent', [InvoiceController::class, 'markAsSent'])->name('mark-sent');
    Route::post('/{id}/void', [InvoiceController::class, 'void'])->name('void');
});

// Customers Routes (if not already defined)
Route::prefix('customers')->name('customers.')->middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('dashboard.coustomer.index');
    })->name('index');

    Route::get('/create', function () {
        return view('dashboard.coustomer.create');
    })->name('create');

    Route::get('/{id}', function ($id) {
        return view('dashboard.coustomer.show', compact('id'));
    })->name('show');
});
