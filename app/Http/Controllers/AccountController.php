<?php

namespace App\Http\Controllers;

use App\Models\Account;
use App\Jobs\SyncAccountsFromZoho;
use App\Services\ZohoBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AccountController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    public function index(Request $request)
    {
        $query = Account::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('account_name', 'like', "%{$search}%")
                  ->orWhere('account_code', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('account_type')) {
            $query->where('account_type', $request->account_type);
        }

        if ($request->filled('is_active')) {
            $query->where('is_active', $request->is_active === 'yes');
        }

        $accounts = $query->orderBy('account_name', 'asc')->paginate(15);

        return view('dashboard.account.index', compact('accounts'));
    }

    public function create()
    {
        return view('dashboard.account.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Note: Zoho Books API for creating accounts might be limited
            // For now, we'll just create in local database
            $account = Account::create([
                'account_name' => $request->account_name,
                'account_code' => $request->account_code,
                'account_type' => $request->account_type,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
                'currency_code' => 'SAR',
                'synced_to_zoho' => false,
            ]);

            DB::commit();

            return redirect()->route('accounts.show', $account)
                           ->with('success', __('dashboard.account_created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating account: ' . $e->getMessage());
            return back()->withInput()
                       ->with('error', __('dashboard.error_creating_account') . ': ' . $e->getMessage());
        }
    }

    public function show(Account $account)
    {
        return view('dashboard.account.show', compact('account'));
    }

    public function edit(Account $account)
    {
        return view('dashboard.account.edit', compact('account'));
    }

    public function update(Request $request, Account $account)
    {
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_type' => 'required|string',
        ]);

        DB::beginTransaction();

        try {
            // Update account in local database
            $account->update([
                'account_name' => $request->account_name,
                'account_code' => $request->account_code,
                'account_type' => $request->account_type,
                'description' => $request->description,
                'is_active' => $request->has('is_active'),
            ]);

            DB::commit();

            return redirect()->route('accounts.show', $account)
                           ->with('success', __('dashboard.account_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating account: ' . $e->getMessage());
            return back()->withInput()
                       ->with('error', __('dashboard.error_updating_account') . ': ' . $e->getMessage());
        }
    }

    public function destroy(Account $account)
    {
        try {
            // Delete from local database only
            $account->delete();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.account_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting account: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_deleting_account') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncFromZoho()
    {
        try {
            set_time_limit(0);
            SyncAccountsFromZoho::dispatchSync();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.accounts_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing accounts: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_accounts') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
