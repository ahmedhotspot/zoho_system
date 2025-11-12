<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CRMController;
use App\Http\Controllers\Api\BooksController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Zoho Books API Routes
Route::prefix('books')->name('books.')->group(function () {

    // Organizations
    Route::get('/organizations', [BooksController::class, 'getOrganizations'])->name('organizations.index');

    // Invoices
    Route::prefix('invoices')->name('invoices.')->group(function () {
        Route::get('/', [BooksController::class, 'getInvoices'])->name('index');
        Route::post('/', [BooksController::class, 'createInvoice'])->name('store');
        Route::get('/{id}', [BooksController::class, 'getInvoice'])->name('show');
        Route::put('/{id}', [BooksController::class, 'updateInvoice'])->name('update');
        Route::delete('/{id}', [BooksController::class, 'deleteInvoice'])->name('destroy');
        Route::post('/{id}/send', [BooksController::class, 'sendInvoice'])->name('send');
        Route::post('/{id}/mark-sent', [BooksController::class, 'markInvoiceAsSent'])->name('mark-sent');
        Route::post('/{id}/void', [BooksController::class, 'voidInvoice'])->name('void');
    });

    // Customers / Contacts
    Route::prefix('customers')->name('customers.')->group(function () {
        Route::get('/', [BooksController::class, 'getCustomers'])->name('index');
        Route::post('/', [BooksController::class, 'createCustomer'])->name('store');
        Route::get('/{id}', [BooksController::class, 'getCustomer'])->name('show');
        Route::put('/{id}', [BooksController::class, 'updateCustomer'])->name('update');
        Route::delete('/{id}', [BooksController::class, 'deleteCustomer'])->name('destroy');
    });

    // Items / Products
    Route::prefix('items')->name('items.')->group(function () {
        Route::get('/', [BooksController::class, 'getItems'])->name('index');
        Route::post('/', [BooksController::class, 'createItem'])->name('store');
        Route::get('/{id}', [BooksController::class, 'getItem'])->name('show');
        Route::put('/{id}', [BooksController::class, 'updateItem'])->name('update');
        Route::delete('/{id}', [BooksController::class, 'deleteItem'])->name('destroy');
    });

    Route::prefix('accounts')->name('accounts.')->group(function () {
    Route::get('/', [BooksController::class, 'getAccounts'])->name('index');
    Route::get('/expense', [BooksController::class, 'getExpenseAccounts'])->name('expense');
    Route::get('/cash-bank', [BooksController::class, 'getCashAndBankAccounts'])->name('cash-bank');
});

    // Estimates
    Route::prefix('estimates')->name('estimates.')->group(function () {
        Route::get('/', [BooksController::class, 'getEstimates'])->name('index');
        Route::post('/', [BooksController::class, 'createEstimate'])->name('store');
        Route::put('/{id}', [BooksController::class, 'updateEstimate'])->name('update');
    });

    // Bills
    Route::prefix('bills')->name('bills.')->group(function () {
        Route::get('/', [BooksController::class, 'getBills'])->name('index');
        Route::post('/', [BooksController::class, 'createBill'])->name('store');
    });

    // Expenses
    Route::prefix('expenses')->name('expenses.')->group(function () {
        Route::get('/', [BooksController::class, 'getExpenses'])->name('index');
        Route::post('/', [BooksController::class, 'createExpense'])->name('store');
    });

    // Payments
    Route::prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [BooksController::class, 'getPayments'])->name('index');
        Route::post('/', [BooksController::class, 'createPayment'])->name('store');
    });
});


