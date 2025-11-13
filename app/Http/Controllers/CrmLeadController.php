<?php

namespace App\Http\Controllers;

use App\Models\CrmLead;
use App\Jobs\SyncLeadsFromZohoCRM;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrmLeadController extends Controller
{
    protected $crm;

    public function __construct(ZohoCRMService $crm)
    {
        $this->crm = $crm;
    }

    public function index(Request $request)
    {
        $query = CrmLead::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('full_name', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        if ($request->filled('lead_status')) {
            $query->where('lead_status', $request->lead_status);
        }

        if ($request->filled('lead_source')) {
            $query->where('lead_source', $request->lead_source);
        }

        if ($request->filled('is_converted')) {
            $query->where('is_converted', $request->is_converted === 'yes');
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(15);

        // Return JSON for AJAX requests
        if ($request->ajax() || $request->has('ajax')) {
            return response()->json([
                'data' => $leads->map(function($lead) {
                    return [
                        'id' => $lead->id,
                        'zoho_id' => $lead->zoho_lead_id,
                        'full_name' => $lead->full_name,
                        'first_name' => $lead->first_name,
                        'last_name' => $lead->last_name,
                        'company' => $lead->company,
                        'email' => $lead->email,
                        'phone' => $lead->phone,
                    ];
                })
            ]);
        }

        return view('dashboard.crm.lead.index', compact('leads'));
    }

    public function create()
    {
        return view('dashboard.crm.lead.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Create in Zoho CRM first
            $zohoData = [
                'First_Name' => $request->first_name,
                'Last_Name' => $request->last_name,
                'Company' => $request->company,
                'Email' => $request->email,
                'Phone' => $request->phone,
                'Mobile' => $request->mobile,
                'Website' => $request->website,
                'Lead_Status' => $request->lead_status ?? 'Not Contacted',
                'Lead_Source' => $request->lead_source,
                'Industry' => $request->industry,
                'Description' => $request->description,
            ];

            $zohoResponse = $this->crm->createLead($zohoData);

            if (isset($zohoResponse['data'][0]['details']['id'])) {
                $zohoLeadId = $zohoResponse['data'][0]['details']['id'];

                // Create in local database
                $lead = CrmLead::create([
                    'zoho_lead_id' => $zohoLeadId,
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'full_name' => trim($request->first_name . ' ' . $request->last_name),
                    'company' => $request->company,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'mobile' => $request->mobile,
                    'website' => $request->website,
                    'lead_status' => $request->lead_status ?? 'Not Contacted',
                    'lead_source' => $request->lead_source,
                    'industry' => $request->industry,
                    'description' => $request->description,
                    'synced_to_zoho' => true,
                    'last_synced_at' => now(),
                ]);

                DB::commit();

                return redirect()->route('crm.leads.show', $lead)
                               ->with('success', __('dashboard.lead_created_successfully'));
            } else {
                throw new \Exception('Failed to create lead in Zoho CRM');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating lead: ' . $e->getMessage());
            return back()->withInput()
                       ->with('error', __('dashboard.error_creating_lead') . ': ' . $e->getMessage());
        }
    }

    public function show(CrmLead $lead)
    {
        return view('dashboard.crm.lead.show', compact('lead'));
    }

    public function edit(CrmLead $lead)
    {
        return view('dashboard.crm.lead.edit', compact('lead'));
    }

    public function update(Request $request, CrmLead $lead)
    {
        $request->validate([
            'last_name' => 'required|string|max:255',
            'company' => 'required|string|max:255',
        ]);

        DB::beginTransaction();

        try {
            // Update in Zoho CRM if synced
            if ($lead->synced_to_zoho && $lead->zoho_lead_id) {
                $zohoData = [
                    'First_Name' => $request->first_name,
                    'Last_Name' => $request->last_name,
                    'Company' => $request->company,
                    'Email' => $request->email,
                    'Phone' => $request->phone,
                    'Mobile' => $request->mobile,
                    'Website' => $request->website,
                    'Lead_Status' => $request->lead_status,
                    'Lead_Source' => $request->lead_source,
                    'Industry' => $request->industry,
                    'Description' => $request->description,
                ];

                $this->crm->updateLead($lead->zoho_lead_id, $zohoData);
            }

            // Update in local database
            $lead->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'full_name' => trim($request->first_name . ' ' . $request->last_name),
                'company' => $request->company,
                'email' => $request->email,
                'phone' => $request->phone,
                'mobile' => $request->mobile,
                'website' => $request->website,
                'lead_status' => $request->lead_status,
                'lead_source' => $request->lead_source,
                'industry' => $request->industry,
                'description' => $request->description,
            ]);

            DB::commit();

            return redirect()->route('crm.leads.show', $lead)
                           ->with('success', __('dashboard.lead_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating lead: ' . $e->getMessage());
            return back()->withInput()
                       ->with('error', __('dashboard.error_updating_lead') . ': ' . $e->getMessage());
        }
    }

    public function destroy(CrmLead $lead)
    {
        try {
            // Delete from Zoho CRM if synced
            if ($lead->synced_to_zoho && $lead->zoho_lead_id) {
                $this->crm->deleteLead($lead->zoho_lead_id);
            }

            // Delete from local database
            $lead->delete();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.lead_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_deleting_lead') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncFromZoho()
    {
        try {
            set_time_limit(0);
            SyncLeadsFromZohoCRM::dispatchSync();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.leads_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing leads: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_leads') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function convert(CrmLead $lead)
    {
        try {
            if ($lead->is_converted) {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.lead_already_converted')
                ], 400);
            }

            if (!$lead->synced_to_zoho || !$lead->zoho_lead_id) {
                return response()->json([
                    'success' => false,
                    'message' => __('dashboard.lead_not_synced_to_zoho')
                ], 400);
            }

            // Convert lead in Zoho CRM
            $this->crm->convertLead($lead->zoho_lead_id);

            // Update local database
            $lead->update([
                'is_converted' => true,
                'converted_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => __('dashboard.lead_converted_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error converting lead: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_converting_lead') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
