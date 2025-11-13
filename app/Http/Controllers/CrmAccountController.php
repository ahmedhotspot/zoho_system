<?php

namespace App\Http\Controllers;

use App\Jobs\SyncAccountsFromZohoCRM;
use App\Models\CrmAccount;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CrmAccountController extends Controller
{
    protected $crm;

    public function __construct(ZohoCRMService $crm)
    {
        $this->crm = $crm;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CrmAccount::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by type
        if ($request->filled('account_type')) {
            $query->byType($request->account_type);
        }

        // Filter by industry
        if ($request->filled('industry')) {
            $query->byIndustry($request->industry);
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->byRating($request->rating);
        }

        $accounts = $query->latest()->paginate(20);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'data' => $accounts->map(function($account) {
                    return [
                        'id' => $account->id,
                        'zoho_id' => $account->zoho_account_id,
                        'account_name' => $account->account_name,
                        'account_type' => $account->account_type,
                        'industry' => $account->industry,
                        'phone' => $account->phone,
                        'website' => $account->website,
                    ];
                })
            ]);
        }

        return view('dashboard.crm.account.index', compact('accounts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.crm.account.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_type' => 'nullable|string',
            'industry' => 'nullable|string',
            'annual_revenue' => 'nullable|string',
            'employees' => 'nullable|integer|min:0',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'billing_street' => 'nullable|string',
            'billing_city' => 'nullable|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_code' => 'nullable|string|max:20',
            'billing_country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            // Create in Zoho CRM first
            $zohoData = [
                'Account_Name' => $validated['account_name'],
            ];

            if (!empty($validated['account_number'])) {
                $zohoData['Account_Number'] = $validated['account_number'];
            }
            if (!empty($validated['account_type'])) {
                $zohoData['Account_Type'] = $validated['account_type'];
            }
            if (!empty($validated['industry'])) {
                $zohoData['Industry'] = $validated['industry'];
            }
            if (!empty($validated['annual_revenue'])) {
                $zohoData['Annual_Revenue'] = $validated['annual_revenue'];
            }
            if (!empty($validated['employees'])) {
                $zohoData['Employees'] = $validated['employees'];
            }
            if (!empty($validated['phone'])) {
                $zohoData['Phone'] = $validated['phone'];
            }
            if (!empty($validated['website'])) {
                $zohoData['Website'] = $validated['website'];
            }
            if (!empty($validated['billing_street'])) {
                $zohoData['Billing_Street'] = $validated['billing_street'];
            }
            if (!empty($validated['billing_city'])) {
                $zohoData['Billing_City'] = $validated['billing_city'];
            }
            if (!empty($validated['billing_state'])) {
                $zohoData['Billing_State'] = $validated['billing_state'];
            }
            if (!empty($validated['billing_code'])) {
                $zohoData['Billing_Code'] = $validated['billing_code'];
            }
            if (!empty($validated['billing_country'])) {
                $zohoData['Billing_Country'] = $validated['billing_country'];
            }
            if (!empty($validated['description'])) {
                $zohoData['Description'] = $validated['description'];
            }

            $response = $this->crm->createAccount($zohoData);

            if (isset($response['data'][0]['details']['id'])) {
                $zohoAccountId = $response['data'][0]['details']['id'];

                // Create in local database
                $validated['zoho_account_id'] = $zohoAccountId;
                $validated['last_synced_at'] = now();
                CrmAccount::create($validated);

                return redirect()->route('crm.accounts.index')
                    ->with('success', __('dashboard.account_created_successfully'));
            }

            throw new \Exception('Failed to get Zoho account ID from response');

        } catch (\Exception $e) {
            Log::error('Error creating account: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_account') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmAccount $account)
    {
        return view('dashboard.crm.account.show', compact('account'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmAccount $account)
    {
        return view('dashboard.crm.account.edit', compact('account'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmAccount $account)
    {
        $validated = $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'nullable|string|max:255',
            'account_type' => 'nullable|string',
            'industry' => 'nullable|string',
            'annual_revenue' => 'nullable|string',
            'employees' => 'nullable|integer|min:0',
            'phone' => 'nullable|string|max:50',
            'website' => 'nullable|url|max:255',
            'billing_street' => 'nullable|string',
            'billing_city' => 'nullable|string|max:100',
            'billing_state' => 'nullable|string|max:100',
            'billing_code' => 'nullable|string|max:20',
            'billing_country' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            // Update in Zoho CRM if synced
            if ($account->zoho_account_id) {
                $zohoData = [
                    'Account_Name' => $validated['account_name'],
                    'Account_Number' => $validated['account_number'],
                    'Account_Type' => $validated['account_type'],
                    'Industry' => $validated['industry'],
                    'Annual_Revenue' => $validated['annual_revenue'],
                    'Employees' => $validated['employees'],
                    'Phone' => $validated['phone'],
                    'Website' => $validated['website'],
                    'Billing_Street' => $validated['billing_street'],
                    'Billing_City' => $validated['billing_city'],
                    'Billing_State' => $validated['billing_state'],
                    'Billing_Code' => $validated['billing_code'],
                    'Billing_Country' => $validated['billing_country'],
                    'Description' => $validated['description'],
                ];

                $this->crm->updateAccount($account->zoho_account_id, $zohoData);
                $validated['last_synced_at'] = now();
            }

            // Update in local database
            $account->update($validated);

            return redirect()->route('crm.accounts.index')
                ->with('success', __('dashboard.account_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating account: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_account') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmAccount $account)
    {
        try {
            // Delete from Zoho CRM if synced
            if ($account->zoho_account_id) {
                $this->crm->deleteAccount($account->zoho_account_id);
            }

            // Delete from local database
            $account->delete();

            return redirect()->route('crm.accounts.index')
                ->with('success', __('dashboard.account_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting account: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_account') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync accounts from Zoho CRM
     */
    public function sync()
    {
        try {
            set_time_limit(0);

            // Run sync synchronously instead of dispatching to queue
            $job = new SyncAccountsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));

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
