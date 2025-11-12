<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\Customer;
use App\Jobs\SyncExpensesFromZoho;
use App\Services\ZohoBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ExpenseController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    public function index(Request $request)
    {
        $query = Expense::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('reference_number', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('account_name', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('is_billable')) {
            $query->where('is_billable', $request->is_billable === 'yes');
        }

        if ($request->filled('customer_id')) {
            $query->where('customer_id', $request->customer_id);
        }

        $expenses = $query->orderBy('expense_date', 'desc')->paginate(15);
        $customers = Customer::orderBy('customer_name')->get();

        return view('dashboard.expense.index', compact('expenses', 'customers'));
    }

    public function create()
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('dashboard.expense.create', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'account_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            DB::beginTransaction();

            $zohoData = [
                'account_name' => $request->account_name,
                'date' => $request->expense_date,
                'amount' => $request->amount,
                'reference_number' => $request->reference_number,
                'description' => $request->description,
                'is_billable' => $request->has('is_billable'),
                'customer_id' => $request->customer_id ? Customer::find($request->customer_id)?->zoho_contact_id : null,
            ];

            $response = $this->books->createExpense($zohoData);
            $zohoExpense = $response['expense'] ?? null;

            if (!$zohoExpense) {
                throw new \Exception('Failed to create expense in Zoho Books');
            }

            $customer = $request->customer_id ? Customer::find($request->customer_id) : null;

            $expense = Expense::create([
                'zoho_expense_id' => $zohoExpense['expense_id'],
                'zoho_account_id' => $zohoExpense['account_id'] ?? null,
                'zoho_customer_id' => $zohoExpense['customer_id'] ?? null,
                'customer_id' => $customer?->id,
                'account_name' => $request->account_name,
                'expense_date' => $request->expense_date,
                'amount' => $request->amount,
                'reference_number' => $request->reference_number,
                'description' => $request->description,
                'is_billable' => $request->has('is_billable'),
                'customer_name' => $customer?->customer_name ?? $customer?->contact_name,
                'currency_code' => 'SAR',
                'total' => $request->amount,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('expenses.show', $expense)
                ->with('success', __('dashboard.expense_created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating expense: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_expense') . ': ' . $e->getMessage());
        }
    }

    public function show(Expense $expense)
    {
        return view('dashboard.expense.show', compact('expense'));
    }

    public function edit(Expense $expense)
    {
        $customers = Customer::orderBy('customer_name')->get();
        return view('dashboard.expense.edit', compact('expense', 'customers'));
    }

    public function update(Request $request, Expense $expense)
    {
        $request->validate([
            'expense_date' => 'required|date',
            'account_name' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
        ]);

        try {
            $expense->update([
                'expense_date' => $request->expense_date,
                'account_name' => $request->account_name,
                'amount' => $request->amount,
                'reference_number' => $request->reference_number,
                'description' => $request->description,
                'is_billable' => $request->has('is_billable'),
                'total' => $request->amount,
            ]);

            return redirect()->route('expenses.show', $expense)
                ->with('success', __('dashboard.expense_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating expense: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_expense') . ': ' . $e->getMessage());
        }
    }

    public function destroy(Expense $expense)
    {
        try {
            if ($expense->synced_to_zoho && $expense->zoho_expense_id) {
                $this->books->deleteExpense($expense->zoho_expense_id);
            }

            $expense->delete();

            return redirect()->route('expenses.index')
                ->with('success', __('dashboard.expense_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting expense: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_expense') . ': ' . $e->getMessage());
        }
    }

    public function syncFromZoho()
    {
        try {
            set_time_limit(0);
            SyncExpensesFromZoho::dispatchSync();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.expenses_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing expenses: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_expenses') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
