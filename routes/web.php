<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\FinancingTypeController;
use App\Http\Controllers\CompanieController;
use App\Http\Controllers\FinancingController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\PermissionController;
use App\Http\Controllers\UserController;
use App\Jobs\SyncCompaniesFromAPI;

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
Route::group(
    [
        'prefix' => \Mcamara\LaravelLocalization\Facades\LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath' ]
    ], function() {

        Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware(['auth', 'permission:view dashboard']);


// Invoices Routes
Route::prefix('invoices')->name('invoices.')->middleware(['auth'])->group(function () {
    Route::get('/', [InvoiceController::class, 'index'])->name('index')->middleware('permission:view invoices');
    Route::get('/create', [InvoiceController::class, 'create'])->name('create')->middleware('permission:create invoices');
    Route::post('/', [InvoiceController::class, 'store'])->name('store')->middleware('permission:create invoices');
    Route::get('/{id}', [InvoiceController::class, 'show'])->name('show')->middleware('permission:view invoices');
    Route::get('/{id}/edit', [InvoiceController::class, 'edit'])->name('edit')->middleware('permission:edit invoices');
    Route::put('/{id}', [InvoiceController::class, 'update'])->name('update')->middleware('permission:edit invoices');
    Route::delete('/{id}', [InvoiceController::class, 'destroy'])->name('destroy')->middleware('permission:delete invoices');

    // Invoice actions
    Route::post('/{id}/send', [InvoiceController::class, 'send'])->name('send')->middleware('permission:edit invoices');
    Route::post('/{id}/mark-sent', [InvoiceController::class, 'markAsSent'])->name('mark-sent')->middleware('permission:edit invoices');
    Route::post('/{id}/void', [InvoiceController::class, 'void'])->name('void')->middleware('permission:edit invoices');

    // Sync invoices from Zoho Books
    Route::post('/sync', [InvoiceController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create invoices');
});

// Customers Routes
Route::prefix('customers')->name('customers.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CustomerController::class, 'index'])->name('index')->middleware('permission:view customers');
    Route::get('/create', [App\Http\Controllers\CustomerController::class, 'create'])->name('create')->middleware('permission:create customers');
    Route::post('/', [App\Http\Controllers\CustomerController::class, 'store'])->name('store')->middleware('permission:create customers');
    Route::get('/{customer}', [App\Http\Controllers\CustomerController::class, 'show'])->name('show')->middleware('permission:view customers');
    Route::get('/{customer}/edit', [App\Http\Controllers\CustomerController::class, 'edit'])->name('edit')->middleware('permission:edit customers');
    Route::put('/{customer}', [App\Http\Controllers\CustomerController::class, 'update'])->name('update')->middleware('permission:edit customers');
    Route::delete('/{customer}', [App\Http\Controllers\CustomerController::class, 'destroy'])->name('destroy')->middleware('permission:delete customers');

    // Sync customers from Zoho Books
    Route::post('/sync', [App\Http\Controllers\CustomerController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create customers');
});

// Items Routes
Route::prefix('items')->name('items.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\ItemController::class, 'index'])->name('index')->middleware('permission:view items');
    Route::get('/create', [App\Http\Controllers\ItemController::class, 'create'])->name('create')->middleware('permission:create items');
    Route::post('/', [App\Http\Controllers\ItemController::class, 'store'])->name('store')->middleware('permission:create items');
    Route::get('/{item}', [App\Http\Controllers\ItemController::class, 'show'])->name('show')->middleware('permission:view items');
    Route::get('/{item}/edit', [App\Http\Controllers\ItemController::class, 'edit'])->name('edit')->middleware('permission:edit items');
    Route::put('/{item}', [App\Http\Controllers\ItemController::class, 'update'])->name('update')->middleware('permission:edit items');
    Route::delete('/{item}', [App\Http\Controllers\ItemController::class, 'destroy'])->name('destroy')->middleware('permission:delete items');

    // Sync items from Zoho Books
    Route::post('/sync', [App\Http\Controllers\ItemController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create items');
});

// Payments Routes
Route::prefix('payments')->name('payments.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\PaymentController::class, 'index'])->name('index')->middleware('permission:view payments');
    Route::get('/create', [App\Http\Controllers\PaymentController::class, 'create'])->name('create')->middleware('permission:create payments');
    Route::post('/', [App\Http\Controllers\PaymentController::class, 'store'])->name('store')->middleware('permission:create payments');
    Route::get('/{payment}', [App\Http\Controllers\PaymentController::class, 'show'])->name('show')->middleware('permission:view payments');
    Route::get('/{payment}/edit', [App\Http\Controllers\PaymentController::class, 'edit'])->name('edit')->middleware('permission:edit payments');
    Route::put('/{payment}', [App\Http\Controllers\PaymentController::class, 'update'])->name('update')->middleware('permission:edit payments');
    Route::delete('/{payment}', [App\Http\Controllers\PaymentController::class, 'destroy'])->name('destroy')->middleware('permission:delete payments');

    // Sync payments from Zoho Books
    Route::post('/sync', [App\Http\Controllers\PaymentController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create payments');
});

// Estimates Routes
Route::prefix('estimates')->name('estimates.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\EstimateController::class, 'index'])->name('index')->middleware('permission:view estimates');
    Route::get('/create', [App\Http\Controllers\EstimateController::class, 'create'])->name('create')->middleware('permission:create estimates');
    Route::post('/', [App\Http\Controllers\EstimateController::class, 'store'])->name('store')->middleware('permission:create estimates');
    Route::get('/{estimate}', [App\Http\Controllers\EstimateController::class, 'show'])->name('show')->middleware('permission:view estimates');
    Route::get('/{estimate}/edit', [App\Http\Controllers\EstimateController::class, 'edit'])->name('edit')->middleware('permission:edit estimates');
    Route::put('/{estimate}', [App\Http\Controllers\EstimateController::class, 'update'])->name('update')->middleware('permission:edit estimates');
    Route::delete('/{estimate}', [App\Http\Controllers\EstimateController::class, 'destroy'])->name('destroy')->middleware('permission:delete estimates');

    // Sync estimates from Zoho Books
    Route::post('/sync', [App\Http\Controllers\EstimateController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create estimates');
});

// Expenses Routes
Route::prefix('expenses')->name('expenses.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\ExpenseController::class, 'index'])->name('index')->middleware('permission:view expenses');
    Route::get('/create', [App\Http\Controllers\ExpenseController::class, 'create'])->name('create')->middleware('permission:create expenses');
    Route::post('/', [App\Http\Controllers\ExpenseController::class, 'store'])->name('store')->middleware('permission:create expenses');
    Route::get('/{expense}', [App\Http\Controllers\ExpenseController::class, 'show'])->name('show')->middleware('permission:view expenses');
    Route::get('/{expense}/edit', [App\Http\Controllers\ExpenseController::class, 'edit'])->name('edit')->middleware('permission:edit expenses');
    Route::put('/{expense}', [App\Http\Controllers\ExpenseController::class, 'update'])->name('update')->middleware('permission:edit expenses');
    Route::delete('/{expense}', [App\Http\Controllers\ExpenseController::class, 'destroy'])->name('destroy')->middleware('permission:delete expenses');

    // Sync expenses from Zoho Books
    Route::post('/sync', [App\Http\Controllers\ExpenseController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create expenses');
});

// Bills Routes
Route::prefix('bills')->name('bills.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\BillController::class, 'index'])->name('index')->middleware('permission:view bills');
    Route::get('/create', [App\Http\Controllers\BillController::class, 'create'])->name('create')->middleware('permission:create bills');
    Route::post('/', [App\Http\Controllers\BillController::class, 'store'])->name('store')->middleware('permission:create bills');
    Route::get('/{bill}', [App\Http\Controllers\BillController::class, 'show'])->name('show')->middleware('permission:view bills');
    Route::get('/{bill}/edit', [App\Http\Controllers\BillController::class, 'edit'])->name('edit')->middleware('permission:edit bills');
    Route::put('/{bill}', [App\Http\Controllers\BillController::class, 'update'])->name('update')->middleware('permission:edit bills');
    Route::delete('/{bill}', [App\Http\Controllers\BillController::class, 'destroy'])->name('destroy')->middleware('permission:delete bills');

    // Sync bills from Zoho Books
    Route::post('/sync', [App\Http\Controllers\BillController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create bills');
});

// Accounts Routes
Route::prefix('accounts')->name('accounts.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\AccountController::class, 'index'])->name('index')->middleware('permission:view accounts');
    Route::get('/create', [App\Http\Controllers\AccountController::class, 'create'])->name('create')->middleware('permission:create accounts');
    Route::post('/', [App\Http\Controllers\AccountController::class, 'store'])->name('store')->middleware('permission:create accounts');
    Route::get('/{account}', [App\Http\Controllers\AccountController::class, 'show'])->name('show')->middleware('permission:view accounts');
    Route::get('/{account}/edit', [App\Http\Controllers\AccountController::class, 'edit'])->name('edit')->middleware('permission:edit accounts');
    Route::put('/{account}', [App\Http\Controllers\AccountController::class, 'update'])->name('update')->middleware('permission:edit accounts');
    Route::delete('/{account}', [App\Http\Controllers\AccountController::class, 'destroy'])->name('destroy')->middleware('permission:delete accounts');

    // Sync accounts from Zoho Books
    Route::post('/sync', [App\Http\Controllers\AccountController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create accounts');
});

// ==========================================
// CRM ROUTES
// ==========================================

// CRM Leads Routes
Route::prefix('crm/leads')->name('crm.leads.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CrmLeadController::class, 'index'])->name('index')->middleware('permission:view crm-leads');
    Route::get('/create', [App\Http\Controllers\CrmLeadController::class, 'create'])->name('create')->middleware('permission:create crm-leads');
    Route::post('/', [App\Http\Controllers\CrmLeadController::class, 'store'])->name('store')->middleware('permission:create crm-leads');
    Route::get('/{lead}', [App\Http\Controllers\CrmLeadController::class, 'show'])->name('show')->middleware('permission:view crm-leads');
    Route::get('/{lead}/edit', [App\Http\Controllers\CrmLeadController::class, 'edit'])->name('edit')->middleware('permission:edit crm-leads');
    Route::put('/{lead}', [App\Http\Controllers\CrmLeadController::class, 'update'])->name('update')->middleware('permission:edit crm-leads');
    Route::delete('/{lead}', [App\Http\Controllers\CrmLeadController::class, 'destroy'])->name('destroy')->middleware('permission:delete crm-leads');

    // Sync leads from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmLeadController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create crm-leads');

    // Convert lead
    Route::post('/{lead}/convert', [App\Http\Controllers\CrmLeadController::class, 'convert'])->name('convert')->middleware('permission:convert crm-leads');
});

// CRM Contacts Routes
Route::prefix('crm/contacts')->name('crm.contacts.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CrmContactController::class, 'index'])->name('index')->middleware('permission:view crm-contacts');
    Route::get('/create', [App\Http\Controllers\CrmContactController::class, 'create'])->name('create')->middleware('permission:create crm-contacts');
    Route::post('/', [App\Http\Controllers\CrmContactController::class, 'store'])->name('store')->middleware('permission:create crm-contacts');
    Route::get('/{contact}', [App\Http\Controllers\CrmContactController::class, 'show'])->name('show')->middleware('permission:view crm-contacts');
    Route::get('/{contact}/edit', [App\Http\Controllers\CrmContactController::class, 'edit'])->name('edit')->middleware('permission:edit crm-contacts');
    Route::put('/{contact}', [App\Http\Controllers\CrmContactController::class, 'update'])->name('update')->middleware('permission:edit crm-contacts');
    Route::delete('/{contact}', [App\Http\Controllers\CrmContactController::class, 'destroy'])->name('destroy')->middleware('permission:delete crm-contacts');

    // Sync contacts from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmContactController::class, 'syncFromZoho'])->name('sync')->middleware('permission:create crm-contacts');
});

// CRM Deals Routes
Route::prefix('crm/deals')->name('crm.deals.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CrmDealController::class, 'index'])->name('index')->middleware('permission:view crm-deals');
    Route::get('/create', [App\Http\Controllers\CrmDealController::class, 'create'])->name('create')->middleware('permission:create crm-deals');
    Route::post('/', [App\Http\Controllers\CrmDealController::class, 'store'])->name('store')->middleware('permission:create crm-deals');
    Route::get('/{deal}', [App\Http\Controllers\CrmDealController::class, 'show'])->name('show')->middleware('permission:view crm-deals');
    Route::get('/{deal}/edit', [App\Http\Controllers\CrmDealController::class, 'edit'])->name('edit')->middleware('permission:edit crm-deals');
    Route::put('/{deal}', [App\Http\Controllers\CrmDealController::class, 'update'])->name('update')->middleware('permission:edit crm-deals');
    Route::delete('/{deal}', [App\Http\Controllers\CrmDealController::class, 'destroy'])->name('destroy')->middleware('permission:delete crm-deals');

    // Sync deals from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmDealController::class, 'sync'])->name('sync')->middleware('permission:create crm-deals');
});

// CRM Accounts Routes
Route::prefix('crm/accounts')->name('crm.accounts.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CrmAccountController::class, 'index'])->name('index')->middleware('permission:view crm-accounts');
    Route::get('/create', [App\Http\Controllers\CrmAccountController::class, 'create'])->name('create')->middleware('permission:create crm-accounts');
    Route::post('/', [App\Http\Controllers\CrmAccountController::class, 'store'])->name('store')->middleware('permission:create crm-accounts');
    Route::get('/{account}', [App\Http\Controllers\CrmAccountController::class, 'show'])->name('show')->middleware('permission:view crm-accounts');
    Route::get('/{account}/edit', [App\Http\Controllers\CrmAccountController::class, 'edit'])->name('edit')->middleware('permission:edit crm-accounts');
    Route::put('/{account}', [App\Http\Controllers\CrmAccountController::class, 'update'])->name('update')->middleware('permission:edit crm-accounts');
    Route::delete('/{account}', [App\Http\Controllers\CrmAccountController::class, 'destroy'])->name('destroy')->middleware('permission:delete crm-accounts');

    // Sync accounts from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmAccountController::class, 'sync'])->name('sync')->middleware('permission:create crm-accounts');
});

// CRM Tasks Routes
Route::prefix('crm/tasks')->name('crm.tasks.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CrmTaskController::class, 'index'])->name('index')->middleware('permission:view crm-tasks');
    Route::get('/create', [App\Http\Controllers\CrmTaskController::class, 'create'])->name('create')->middleware('permission:create crm-tasks');
    Route::post('/', [App\Http\Controllers\CrmTaskController::class, 'store'])->name('store')->middleware('permission:create crm-tasks');
    Route::get('/{task}', [App\Http\Controllers\CrmTaskController::class, 'show'])->name('show')->middleware('permission:view crm-tasks');
    Route::get('/{task}/edit', [App\Http\Controllers\CrmTaskController::class, 'edit'])->name('edit')->middleware('permission:edit crm-tasks');
    Route::put('/{task}', [App\Http\Controllers\CrmTaskController::class, 'update'])->name('update')->middleware('permission:edit crm-tasks');
    Route::delete('/{task}', [App\Http\Controllers\CrmTaskController::class, 'destroy'])->name('destroy')->middleware('permission:delete crm-tasks');

    // Sync tasks from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmTaskController::class, 'sync'])->name('sync')->middleware('permission:create crm-tasks');
});

// CRM Calls Routes
Route::prefix('crm/calls')->name('crm.calls.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CrmCallController::class, 'index'])->name('index')->middleware('permission:view crm-calls');
    Route::get('/create', [App\Http\Controllers\CrmCallController::class, 'create'])->name('create')->middleware('permission:create crm-calls');
    Route::post('/', [App\Http\Controllers\CrmCallController::class, 'store'])->name('store')->middleware('permission:create crm-calls');
    Route::get('/{call}', [App\Http\Controllers\CrmCallController::class, 'show'])->name('show')->middleware('permission:view crm-calls');
    Route::get('/{call}/edit', [App\Http\Controllers\CrmCallController::class, 'edit'])->name('edit')->middleware('permission:edit crm-calls');
    Route::put('/{call}', [App\Http\Controllers\CrmCallController::class, 'update'])->name('update')->middleware('permission:edit crm-calls');
    Route::delete('/{call}', [App\Http\Controllers\CrmCallController::class, 'destroy'])->name('destroy')->middleware('permission:delete crm-calls');

    // Sync calls from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmCallController::class, 'sync'])->name('sync')->middleware('permission:create crm-calls');
});

// CRM Events Routes
Route::prefix('crm/events')->name('crm.events.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CrmEventController::class, 'index'])->name('index')->middleware('permission:view crm-events');
    Route::get('/create', [App\Http\Controllers\CrmEventController::class, 'create'])->name('create')->middleware('permission:create crm-events');
    Route::post('/', [App\Http\Controllers\CrmEventController::class, 'store'])->name('store')->middleware('permission:create crm-events');
    Route::get('/{event}', [App\Http\Controllers\CrmEventController::class, 'show'])->name('show')->middleware('permission:view crm-events');
    Route::get('/{event}/edit', [App\Http\Controllers\CrmEventController::class, 'edit'])->name('edit')->middleware('permission:edit crm-events');
    Route::put('/{event}', [App\Http\Controllers\CrmEventController::class, 'update'])->name('update')->middleware('permission:edit crm-events');
    Route::delete('/{event}', [App\Http\Controllers\CrmEventController::class, 'destroy'])->name('destroy')->middleware('permission:delete crm-events');

    // Sync events from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmEventController::class, 'sync'])->name('sync')->middleware('permission:create crm-events');
});

// CRM Notes Routes
Route::prefix('crm/notes')->name('crm.notes.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\CrmNoteController::class, 'index'])->name('index')->middleware('permission:view crm-notes');
    Route::get('/create', [App\Http\Controllers\CrmNoteController::class, 'create'])->name('create')->middleware('permission:create crm-notes');
    Route::post('/', [App\Http\Controllers\CrmNoteController::class, 'store'])->name('store')->middleware('permission:create crm-notes');
    Route::get('/{note}', [App\Http\Controllers\CrmNoteController::class, 'show'])->name('show')->middleware('permission:view crm-notes');
    Route::get('/{note}/edit', [App\Http\Controllers\CrmNoteController::class, 'edit'])->name('edit')->middleware('permission:edit crm-notes');
    Route::put('/{note}', [App\Http\Controllers\CrmNoteController::class, 'update'])->name('update')->middleware('permission:edit crm-notes');
    Route::delete('/{note}', [App\Http\Controllers\CrmNoteController::class, 'destroy'])->name('destroy')->middleware('permission:delete crm-notes');

    // Sync notes from Zoho CRM
    Route::post('/sync', [App\Http\Controllers\CrmNoteController::class, 'sync'])->name('sync')->middleware('permission:create crm-notes');
});

// Financing Types Routes
Route::prefix('financing-types')->name('financing-types.')->middleware(['auth'])->group(function () {
    Route::get('/', [FinancingTypeController::class, 'index'])->name('index')->middleware('permission:view financing-types');
    Route::get('/create', [FinancingTypeController::class, 'create'])->name('create')->middleware('permission:create financing-types');
    Route::post('/', [FinancingTypeController::class, 'store'])->name('store')->middleware('permission:create financing-types');
    Route::get('/{id}/edit', [FinancingTypeController::class, 'edit'])->name('edit')->middleware('permission:edit financing-types');
    Route::put('/{id}', [FinancingTypeController::class, 'update'])->name('update')->middleware('permission:edit financing-types');
    Route::delete('/{id}', [FinancingTypeController::class, 'destroy'])->name('destroy')->middleware('permission:delete financing-types');
});

// Companies Routes
Route::prefix('companies')->name('companies.')->middleware(['auth'])->group(function () {
    Route::get('/', [CompanieController::class, 'index'])->name('index')->middleware('permission:view companies');
    Route::get('/create', [CompanieController::class, 'create'])->name('create')->middleware('permission:create companies');
    Route::post('/', [CompanieController::class, 'store'])->name('store')->middleware('permission:create companies');
    Route::get('/{id}/edit', [CompanieController::class, 'edit'])->name('edit')->middleware('permission:edit companies');
    Route::put('/{id}', [CompanieController::class, 'update'])->name('update')->middleware('permission:edit companies');
    Route::delete('/{id}', [CompanieController::class, 'destroy'])->name('destroy')->middleware('permission:delete companies');

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

Route::prefix('financings')->name('financings.')->middleware(['auth'])->group(function(){
    Route::get('/', [FinancingController::class, 'index'])->name('index')->middleware('permission:view financings');
    Route::get('/{id}', [FinancingController::class, 'show'])->name('show')->middleware('permission:view financings');
    Route::post('/{id}/update-price', [FinancingController::class, 'updatePrice'])->name('update-price')->middleware('permission:edit financings');
});

// Roles Routes
Route::prefix('roles')->name('roles.')->middleware(['auth'])->group(function () {
    Route::get('/', [RoleController::class, 'index'])->name('index')->middleware('permission:view roles');
    Route::get('/create', [RoleController::class, 'create'])->name('create')->middleware('permission:create roles');
    Route::post('/', [RoleController::class, 'store'])->name('store')->middleware('permission:create roles');
    Route::get('/{id}/edit', [RoleController::class, 'edit'])->name('edit')->middleware('permission:edit roles');
    Route::put('/{id}', [RoleController::class, 'update'])->name('update')->middleware('permission:edit roles');
    Route::delete('/{id}', [RoleController::class, 'destroy'])->name('destroy')->middleware('permission:delete roles');
});

// Permissions Routes
Route::prefix('permissions')->name('permissions.')->middleware(['auth'])->group(function () {
    Route::get('/', [PermissionController::class, 'index'])->name('index')->middleware('permission:view permissions');
    Route::get('/create', [PermissionController::class, 'create'])->name('create')->middleware('role:super-admin');
    Route::post('/', [PermissionController::class, 'store'])->name('store')->middleware('role:super-admin');
    Route::get('/{id}/edit', [PermissionController::class, 'edit'])->name('edit')->middleware('role:super-admin');
    Route::put('/{id}', [PermissionController::class, 'update'])->name('update')->middleware('role:super-admin');
    Route::delete('/{id}', [PermissionController::class, 'destroy'])->name('destroy')->middleware('role:super-admin');
});

// Users Routes
Route::prefix('users')->name('users.')->middleware(['auth'])->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index')->middleware('permission:view users');
    Route::get('/create', [UserController::class, 'create'])->name('create')->middleware('permission:create users');
    Route::post('/', [UserController::class, 'store'])->name('store')->middleware('permission:create users');
    Route::get('/{id}/edit', [UserController::class, 'edit'])->name('edit')->middleware('permission:edit users');
    Route::put('/{id}', [UserController::class, 'update'])->name('update')->middleware('permission:edit users');
    Route::delete('/{id}', [UserController::class, 'destroy'])->name('destroy')->middleware('permission:delete users');
});

});
