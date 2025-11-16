<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\FinancingTypeController;
use App\Http\Controllers\CompanieController;
use App\Jobs\SyncCompaniesFromAPI;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::group(
    [
        'prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function() {

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

    // Sync invoices from Zoho Books
    Route::post('/sync', [InvoiceController::class, 'syncFromZoho'])->name('sync');
});

// Customers Routes
Route::prefix('customers')->name('customers.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CustomerController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CustomerController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CustomerController::class, 'store'])->name('store');
    Route::get('/{customer}', [App\Http\Controllers\CustomerController::class, 'show'])->name('show');
    Route::get('/{customer}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('edit');
    Route::put('/{customer}', [App\Http\Controllers\CustomerController::class, 'update'])->name('update');
    Route::delete('/{customer}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('destroy');

    // Sync customers from Zoho Books
    Route::post('/sync', [App\Http\Controllers\CustomerController::class, 'syncFromZoho'])->name('sync');
});

// Items Routes
Route::prefix('items')->name('items.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\ItemController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\ItemController::class, 'store'])->name('store');
    Route::get('/{item}', [App\Http\Controllers\ItemController::class, 'show'])->name('show');
    Route::get('/{item}/edit', [App\Http\Controllers\ItemController::class, 'edit'])->name('edit');
    Route::put('/{item}', [App\Http\Controllers\ItemController::class, 'update'])->name('update');
    Route::delete('/{item}', [App\Http\Controllers\ItemController::class, 'destroy'])->name('destroy');

    // Sync items from Zoho Books
    Route::post('/sync', [App\Http\Controllers\ItemController::class, 'syncFromZoho'])->name('sync');
});

// Payments Routes
Route::prefix('payments')->name('payments.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\PaymentController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\PaymentController::class, 'store'])->name('store');
    Route::get('/{payment}', [App\Http\Controllers\PaymentController::class, 'show'])->name('show');
    Route::get('/{payment}/edit', [App\Http\Controllers\PaymentController::class, 'edit'])->name('edit');
    Route::put('/{payment}', [App\Http\Controllers\PaymentController::class, 'update'])->name('update');
    Route::delete('/{payment}', [App\Http\Controllers\PaymentController::class, 'destroy'])->name('destroy');

    // Sync payments from Zoho Books
    Route::post('/sync', [App\Http\Controllers\PaymentController::class, 'syncFromZoho'])->name('sync');
});

// Estimates Routes
Route::prefix('estimates')->name('estimates.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\EstimateController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\EstimateController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\EstimateController::class, 'store'])->name('store');
    Route::get('/{estimate}', [App\Http\Controllers\EstimateController::class, 'show'])->name('show');
    Route::get('/{estimate}/edit', [App\Http\Controllers\EstimateController::class, 'edit'])->name('edit');
    Route::put('/{estimate}', [App\Http\Controllers\EstimateController::class, 'update'])->name('update');
    Route::delete('/{estimate}', [App\Http\Controllers\EstimateController::class, 'destroy'])->name('destroy');

    // Sync estimates from Zoho Books
    Route::post('/sync', [App\Http\Controllers\EstimateController::class, 'syncFromZoho'])->name('sync');
});

// Expenses Routes
Route::prefix('expenses')->name('expenses.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\ExpenseController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\ExpenseController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\ExpenseController::class, 'store'])->name('store');
    Route::get('/{expense}', [App\Http\Controllers\ExpenseController::class, 'show'])->name('show');
    Route::get('/{expense}/edit', [App\Http\Controllers\ExpenseController::class, 'edit'])->name('edit');
    Route::put('/{expense}', [App\Http\Controllers\ExpenseController::class, 'update'])->name('update');
    Route::delete('/{expense}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('destroy');

    // Sync expenses from Zoho Books
    Route::post('/sync', [App\Http\Controllers\ExpenseController::class, 'syncFromZoho'])->name('sync');
});

// Bills Routes
Route::prefix('bills')->name('bills.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\BillController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\BillController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\BillController::class, 'store'])->name('store');
    Route::get('/{bill}', [App\Http\Controllers\BillController::class, 'show'])->name('show');
    Route::get('/{bill}/edit', [App\Http\Controllers\BillController::class, 'edit'])->name('edit');
    Route::put('/{bill}', [App\Http\Controllers\BillController::class, 'update'])->name('update');
    Route::delete('/{bill}', [App\Http\Controllers\BillController::class, 'destroy'])->name('destroy');

    // Sync bills from Zoho Books
    Route::post('/sync', [App\Http\Controllers\BillController::class, 'syncFromZoho'])->name('sync');
});

// Accounts Routes
Route::prefix('accounts')->name('accounts.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\AccountController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\AccountController::class, 'store'])->name('store');
    Route::get('/{account}', [App\Http\Controllers\AccountController::class, 'show'])->name('show');
    Route::get('/{account}/edit', [App\Http\Controllers\AccountController::class, 'edit'])->name('edit');
    Route::put('/{account}', [App\Http\Controllers\AccountController::class, 'update'])->name('update');
    Route::delete('/{account}', [App\Http\Controllers\AccountController::class, 'destroy'])->name('destroy');

    // Sync accounts from Zoho Books
    Route::post('/sync', [App\Http\Controllers\AccountController::class, 'syncFromZoho'])->name('sync');
});

// ==========================================
// CRM ROUTES
// ==========================================

// CRM Leads Routes
Route::prefix('crm/leads')->name('crm.leads.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CrmLeadController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CrmLeadController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CrmLeadController::class, 'store'])->name('store');
    Route::get('/{lead}', [App\Http\Controllers\CrmLeadController::class, 'show'])->name('show');
    Route::get('/{lead}/edit', [App\Http\Controllers\CrmLeadController::class, 'edit'])->name('edit');
    Route::put('/{lead}', [App\Http\Controllers\CrmLeadController::class, 'update'])->name('update');
    Route::delete('/{lead}', [App\Http\Controllers\CrmLeadController::class, 'destroy'])->name('destroy');

    // Sync leads from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmLeadController::class, 'syncFromZoho'])->name('sync');

    // Convert lead
    Route::post('/{lead}/convert', [App\Http\Controllers\CrmLeadController::class, 'convert'])->name('convert');
});

// CRM Contacts Routes
Route::prefix('crm/contacts')->name('crm.contacts.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CrmContactController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CrmContactController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CrmContactController::class, 'store'])->name('store');
    Route::get('/{contact}', [App\Http\Controllers\CrmContactController::class, 'show'])->name('show');
    Route::get('/{contact}/edit', [App\Http\Controllers\CrmContactController::class, 'edit'])->name('edit');
    Route::put('/{contact}', [App\Http\Controllers\CrmContactController::class, 'update'])->name('update');
    Route::delete('/{contact}', [App\Http\Controllers\CrmContactController::class, 'destroy'])->name('destroy');

    // Sync contacts from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmContactController::class, 'syncFromZoho'])->name('sync');
});

// CRM Deals Routes
Route::prefix('crm/deals')->name('crm.deals.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CrmDealController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CrmDealController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CrmDealController::class, 'store'])->name('store');
    Route::get('/{deal}', [App\Http\Controllers\CrmDealController::class, 'show'])->name('show');
    Route::get('/{deal}/edit', [App\Http\Controllers\CrmDealController::class, 'edit'])->name('edit');
    Route::put('/{deal}', [App\Http\Controllers\CrmDealController::class, 'update'])->name('update');
    Route::delete('/{deal}', [App\Http\Controllers\CrmDealController::class, 'destroy'])->name('destroy');

    // Sync deals from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmDealController::class, 'sync'])->name('sync');
});

// CRM Accounts Routes
Route::prefix('crm/accounts')->name('crm.accounts.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CrmAccountController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CrmAccountController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CrmAccountController::class, 'store'])->name('store');
    Route::get('/{account}', [App\Http\Controllers\CrmAccountController::class, 'show'])->name('show');
    Route::get('/{account}/edit', [App\Http\Controllers\CrmAccountController::class, 'edit'])->name('edit');
    Route::put('/{account}', [App\Http\Controllers\CrmAccountController::class, 'update'])->name('update');
    Route::delete('/{account}', [App\Http\Controllers\CrmAccountController::class, 'destroy'])->name('destroy');

    // Sync accounts from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmAccountController::class, 'sync'])->name('sync');
});

// CRM Tasks Routes
Route::prefix('crm/tasks')->name('crm.tasks.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CrmTaskController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CrmTaskController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CrmTaskController::class, 'store'])->name('store');
    Route::get('/{task}', [App\Http\Controllers\CrmTaskController::class, 'show'])->name('show');
    Route::get('/{task}/edit', [App\Http\Controllers\CrmTaskController::class, 'edit'])->name('edit');
    Route::put('/{task}', [App\Http\Controllers\CrmTaskController::class, 'update'])->name('update');
    Route::delete('/{task}', [App\Http\Controllers\CrmTaskController::class, 'destroy'])->name('destroy');

    // Sync tasks from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmTaskController::class, 'sync'])->name('sync');
});

// CRM Calls Routes
Route::prefix('crm/calls')->name('crm.calls.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CrmCallController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CrmCallController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CrmCallController::class, 'store'])->name('store');
    Route::get('/{call}', [App\Http\Controllers\CrmCallController::class, 'show'])->name('show');
    Route::get('/{call}/edit', [App\Http\Controllers\CrmCallController::class, 'edit'])->name('edit');
    Route::put('/{call}', [App\Http\Controllers\CrmCallController::class, 'update'])->name('update');
    Route::delete('/{call}', [App\Http\Controllers\CrmCallController::class, 'destroy'])->name('destroy');

    // Sync calls from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmCallController::class, 'sync'])->name('sync');
});

// CRM Events Routes
Route::prefix('crm/events')->name('crm.events.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CrmEventController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CrmEventController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CrmEventController::class, 'store'])->name('store');
    Route::get('/{event}', [App\Http\Controllers\CrmEventController::class, 'show'])->name('show');
    Route::get('/{event}/edit', [App\Http\Controllers\CrmEventController::class, 'edit'])->name('edit');
    Route::put('/{event}', [App\Http\Controllers\CrmEventController::class, 'update'])->name('update');
    Route::delete('/{event}', [App\Http\Controllers\CrmEventController::class, 'destroy'])->name('destroy');

    // Sync events from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmEventController::class, 'sync'])->name('sync');
});

// CRM Notes Routes
Route::prefix('crm/notes')->name('crm.notes.')->middleware('auth')->group(function () {
    Route::get('/', [App\Http\Controllers\CrmNoteController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\CrmNoteController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\CrmNoteController::class, 'store'])->name('store');
    Route::get('/{note}', [App\Http\Controllers\CrmNoteController::class, 'show'])->name('show');
    Route::get('/{note}/edit', [App\Http\Controllers\CrmNoteController::class, 'edit'])->name('edit');
    Route::put('/{note}', [App\Http\Controllers\CrmNoteController::class, 'update'])->name('update');
    Route::delete('/{note}', [App\Http\Controllers\CrmNoteController::class, 'destroy'])->name('destroy');

    // Sync notes from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmNoteController::class, 'sync'])->name('sync');
});

// Financing Types Routes
Route::prefix('financing-types')->name('financing-types.')->middleware('auth')->group(function () {
    Route::get('/', [FinancingTypeController::class, 'index'])->name('index');
    Route::get('/create', [FinancingTypeController::class, 'create'])->name('create');
    Route::post('/', [FinancingTypeController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [FinancingTypeController::class, 'edit'])->name('edit');
    Route::put('/{id}', [FinancingTypeController::class, 'update'])->name('update');
    Route::delete('/{id}', [FinancingTypeController::class, 'destroy'])->name('destroy');
});

// Companies Routes
Route::prefix('companies')->name('companies.')->middleware('auth')->group(function () {
    Route::get('/', [CompanieController::class, 'index'])->name('index');
    Route::get('/create', [CompanieController::class, 'create'])->name('create');
    Route::post('/', [CompanieController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [CompanieController::class, 'edit'])->name('edit');
    Route::put('/{id}', [CompanieController::class, 'update'])->name('update');
    Route::delete('/{id}', [CompanieController::class, 'destroy'])->name('destroy');

    // Sync companies from API
    Route::get('/sync', function() {
        try {
           $job = new SyncCompaniesFromAPI();
            $job->handle();
            return redirect()->route('companies.index')->with('success', 'Companies sync job dispatched successfully!');
        } catch (\Exception $e) {
        }
    })->name('sync');
});

});
