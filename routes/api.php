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


// Zoho CRM API Routes
Route::prefix('crm')->name('crm.')->group(function () {

    // Leads
    Route::prefix('leads')->name('leads.')->group(function () {
        Route::get('/', [CRMController::class, 'getLeads'])->name('index');
        Route::post('/', [CRMController::class, 'createLead'])->name('store');
        Route::get('/{id}', [CRMController::class, 'getLead'])->name('show');
        Route::put('/{id}', [CRMController::class, 'updateLead'])->name('update');
        Route::delete('/{id}', [CRMController::class, 'deleteLead'])->name('destroy');
        Route::post('/{id}/convert', [CRMController::class, 'convertLead'])->name('convert');
    });

    // Contacts
    Route::prefix('contacts')->name('contacts.')->group(function () {
        Route::get('/', [CRMController::class, 'getContacts'])->name('index');
        Route::post('/', [CRMController::class, 'createContact'])->name('store');
    });

    // Accounts
    Route::prefix('accounts')->name('accounts.')->group(function () {
        Route::get('/', [CRMController::class, 'getAccounts'])->name('index');
        Route::post('/', [CRMController::class, 'createAccount'])->name('store');
    });

    // Deals
    Route::prefix('deals')->name('deals.')->group(function () {
        Route::get('/', [CRMController::class, 'getDeals'])->name('index');
        Route::post('/', [CRMController::class, 'createDeal'])->name('store');
    });

    // Search
    Route::get('/search', [CRMController::class, 'search'])->name('search');


// ==========================================
    // TASKS
    // ==========================================
    Route::prefix('tasks')->name('tasks.')->group(function () {
        Route::get('/', [CRMController::class, 'getTasks'])->name('index');
        Route::post('/', [CRMController::class, 'createTask'])->name('store');
        Route::get('/{id}', [CRMController::class, 'getTask'])->name('show');
        Route::put('/{id}', [CRMController::class, 'updateTask'])->name('update');
        Route::delete('/{id}', [CRMController::class, 'deleteTask'])->name('destroy');
    });

    // ==========================================
    // CALLS
    // ==========================================
    Route::prefix('calls')->name('calls.')->group(function () {
        Route::get('/', [CRMController::class, 'getCalls'])->name('index');
        Route::post('/', [CRMController::class, 'createCall'])->name('store');
        Route::get('/{id}', [CRMController::class, 'getCall'])->name('show');
        Route::put('/{id}', [CRMController::class, 'updateCall'])->name('update');
        Route::delete('/{id}', [CRMController::class, 'deleteCall'])->name('destroy');
    });

    // ==========================================
    // MEETINGS / EVENTS
    // ==========================================
    Route::prefix('meetings')->name('meetings.')->group(function () {
        Route::get('/', [CRMController::class, 'getMeetings'])->name('index');
        Route::post('/', [CRMController::class, 'createMeeting'])->name('store');
        Route::get('/{id}', [CRMController::class, 'getMeeting'])->name('show');
        Route::put('/{id}', [CRMController::class, 'updateMeeting'])->name('update');
    });

    // ==========================================
    // NOTES
    // ==========================================
    Route::prefix('notes')->name('notes.')->group(function () {
        Route::get('/{module}/{recordId}', [CRMController::class, 'getNotes'])->name('list');
        Route::post('/{module}/{recordId}', [CRMController::class, 'createNote'])->name('store');
        Route::put('/{id}', [CRMController::class, 'updateNote'])->name('update');
        Route::delete('/{id}', [CRMController::class, 'deleteNote'])->name('destroy');
    });

    // ==========================================
    // SEARCH
    // ==========================================
    Route::prefix('search')->name('search.')->group(function () {
        Route::get('/', [CRMController::class, 'search'])->name('general');
        Route::get('/email', [CRMController::class, 'searchByEmail'])->name('email');
        Route::get('/phone', [CRMController::class, 'searchByPhone'])->name('phone');
        Route::get('/name', [CRMController::class, 'searchByName'])->name('name');
    });

    // ==========================================
    // BULK OPERATIONS
    // ==========================================
    Route::prefix('bulk')->name('bulk.')->group(function () {
        Route::post('/{module}/create', [CRMController::class, 'bulkCreate'])->name('create');
        Route::put('/{module}/update', [CRMController::class, 'bulkUpdate'])->name('update');
        Route::delete('/{module}/delete', [CRMController::class, 'bulkDelete'])->name('delete');
    });

    // ==========================================
    // RELATED RECORDS
    // ==========================================
    Route::prefix('related')->name('related.')->group(function () {
        Route::get('/{module}/{recordId}/{relatedModule}', [CRMController::class, 'getRelatedRecords'])->name('list');
        Route::put('/{module}/{recordId}/{relatedModule}', [CRMController::class, 'associateRelatedRecords'])->name('associate');
        Route::delete('/{module}/{recordId}/{relatedModule}', [CRMController::class, 'dissociateRelatedRecords'])->name('dissociate');
    });

    // ==========================================
    // METADATA
    // ==========================================
    Route::prefix('metadata')->name('metadata.')->group(function () {
        Route::get('/modules', [CRMController::class, 'getAllModules'])->name('modules');
        Route::get('/modules/{module}', [CRMController::class, 'getModuleMetadata'])->name('module');
        Route::get('/fields/{module}', [CRMController::class, 'getFieldsMetadata'])->name('fields');
    });

    // ==========================================
    // USERS
    // ==========================================
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [CRMController::class, 'getUsers'])->name('index');
        Route::get('/current', [CRMController::class, 'getCurrentUser'])->name('current');
        Route::get('/{id}', [CRMController::class, 'getUser'])->name('show');
    });

});
