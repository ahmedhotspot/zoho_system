<?php

namespace App\Http\Controllers;

use App\Jobs\SyncDealsFromZohoCRM;
use App\Models\CrmDeal;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CrmDealController extends Controller
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
        $query = CrmDeal::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by stage
        if ($request->filled('stage')) {
            $query->byStage($request->stage);
        }

        // Filter by type
        if ($request->filled('type')) {
            $query->byType($request->type);
        }

        $deals = $query->latest()->paginate(20);

        return view('dashboard.crm.deal.index', compact('deals'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.crm.deal.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'deal_name' => 'required|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'stage' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
            'closing_date' => 'nullable|date',
            'type' => 'nullable|string',
            'lead_source' => 'nullable|string',
            'next_step' => 'nullable|string|max:255',
            'probability' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            // Create in Zoho CRM first
            $zohoData = [
                'Deal_Name' => $validated['deal_name'],
                'Stage' => $validated['stage'],
            ];

            if (!empty($validated['account_name'])) {
                $zohoData['Account_Name'] = $validated['account_name'];
            }
            if (!empty($validated['amount'])) {
                $zohoData['Amount'] = $validated['amount'];
            }
            if (!empty($validated['closing_date'])) {
                $zohoData['Closing_Date'] = $validated['closing_date'];
            }
            if (!empty($validated['type'])) {
                $zohoData['Type'] = $validated['type'];
            }
            if (!empty($validated['lead_source'])) {
                $zohoData['Lead_Source'] = $validated['lead_source'];
            }
            if (!empty($validated['next_step'])) {
                $zohoData['Next_Step'] = $validated['next_step'];
            }
            if (!empty($validated['probability'])) {
                $zohoData['Probability'] = $validated['probability'];
            }
            if (!empty($validated['description'])) {
                $zohoData['Description'] = $validated['description'];
            }

            $response = $this->crm->createDeal($zohoData);

            if (isset($response['data'][0]['code']) && $response['data'][0]['code'] === 'SUCCESS') {
                $zohoDealId = $response['data'][0]['details']['id'];

                // Create in local database
                $validated['zoho_deal_id'] = $zohoDealId;
                $validated['synced_to_zoho'] = true;
                $validated['last_synced_at'] = now();

                CrmDeal::create($validated);

                return redirect()->route('crm.deals.index')
                    ->with('success', __('dashboard.deal_created_successfully'));
            } else {
                return back()->with('error', __('dashboard.error_creating_deal'))->withInput();
            }

        } catch (\Exception $e) {
            Log::error('Error creating deal: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_creating_deal') . ': ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmDeal $deal)
    {
        return view('dashboard.crm.deal.show', compact('deal'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmDeal $deal)
    {
        return view('dashboard.crm.deal.edit', compact('deal'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmDeal $deal)
    {
        $validated = $request->validate([
            'deal_name' => 'required|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'stage' => 'required|string',
            'amount' => 'nullable|numeric|min:0',
            'closing_date' => 'nullable|date',
            'type' => 'nullable|string',
            'lead_source' => 'nullable|string',
            'next_step' => 'nullable|string|max:255',
            'probability' => 'nullable|numeric|min:0|max:100',
            'description' => 'nullable|string',
        ]);

        try {
            // Update in Zoho CRM if synced
            if ($deal->synced_to_zoho && $deal->zoho_deal_id) {
                $zohoData = [
                    'Deal_Name' => $validated['deal_name'],
                    'Stage' => $validated['stage'],
                ];

                if (!empty($validated['account_name'])) {
                    $zohoData['Account_Name'] = $validated['account_name'];
                }
                if (!empty($validated['amount'])) {
                    $zohoData['Amount'] = $validated['amount'];
                }
                if (!empty($validated['closing_date'])) {
                    $zohoData['Closing_Date'] = $validated['closing_date'];
                }
                if (!empty($validated['type'])) {
                    $zohoData['Type'] = $validated['type'];
                }
                if (!empty($validated['lead_source'])) {
                    $zohoData['Lead_Source'] = $validated['lead_source'];
                }
                if (!empty($validated['next_step'])) {
                    $zohoData['Next_Step'] = $validated['next_step'];
                }
                if (!empty($validated['probability'])) {
                    $zohoData['Probability'] = $validated['probability'];
                }
                if (!empty($validated['description'])) {
                    $zohoData['Description'] = $validated['description'];
                }

                $this->crm->updateDeal($deal->zoho_deal_id, $zohoData);
                $validated['last_synced_at'] = now();
            }

            // Update in local database
            $deal->update($validated);

            return redirect()->route('crm.deals.index')
                ->with('success', __('dashboard.deal_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating deal: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_updating_deal') . ': ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmDeal $deal)
    {
        try {
            // Delete from Zoho CRM if synced
            if ($deal->synced_to_zoho && $deal->zoho_deal_id) {
                $this->crm->deleteDeal($deal->zoho_deal_id);
            }

            // Delete from local database
            $deal->delete();

            return redirect()->route('crm.deals.index')
                ->with('success', __('dashboard.deal_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting deal: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_deal') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync deals from Zoho CRM
     */
    public function sync()
    {
        try {
            set_time_limit(0);

            // Run sync synchronously instead of dispatching to queue
            $job = new SyncDealsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));

            return response()->json([
                'success' => true,
                'message' => __('dashboard.deals_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing deals: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_deals') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
