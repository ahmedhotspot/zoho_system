<?php

namespace App\Http\Controllers;

use App\Jobs\SyncCallsFromZohoCRM;
use App\Models\CrmCall;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrmCallController extends Controller
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
        $query = CrmCall::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by call type
        if ($request->filled('call_type')) {
            $query->byCallType($request->call_type);
        }

        // Filter by call result
        if ($request->filled('call_result')) {
            $query->byCallResult($request->call_result);
        }

        $calls = $query->latest('call_start_time')->paginate(20);

        return view('dashboard.crm.call.index', compact('calls'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.crm.call.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'call_type' => 'nullable|string',
            'call_start_time' => 'nullable|date',
            'call_duration' => 'nullable|string',
            'call_result' => 'nullable|string',
            'call_purpose' => 'nullable|string',
            'description' => 'nullable|string',
            'related_to_type' => 'nullable|string|in:Leads,Contacts,Deals,Accounts',
            'related_to_id' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Prepare data for Zoho CRM
            $zohoData = [
                'Subject' => $validated['subject'],
            ];

            // Add Call_Start_Time if provided
            // Zoho expects format: yyyy-MM-dd'T'HH:mm:ss+HH:mm or yyyy-MM-dd'T'HH:mm:ss.SSSZ
            if (!empty($validated['call_start_time'])) {
                try {
                    $dateTime = new \DateTime($validated['call_start_time']);
                    // Try ISO 8601 format with timezone
                    $zohoData['Call_Start_Time'] = $dateTime->format('c'); // ISO 8601: 2025-11-12T18:30:00+03:00
                } catch (\Exception $e) {
                    Log::warning('Invalid call_start_time format: ' . $validated['call_start_time']);
                }
            }

            // Add optional fields
            if (!empty($validated['call_type'])) {
                $zohoData['Call_Type'] = $validated['call_type'];
            }
            if (!empty($validated['call_duration'])) {
                // Duration should be in minutes (integer or string like "30")
                $zohoData['Call_Duration'] = $validated['call_duration'];
            }
            if (!empty($validated['call_result'])) {
                $zohoData['Call_Result'] = $validated['call_result'];
            }
            if (!empty($validated['call_purpose'])) {
                $zohoData['Call_Purpose'] = $validated['call_purpose'];
            }
            if (!empty($validated['description'])) {
                $zohoData['Description'] = $validated['description'];
            }

            // Add related record if provided
            if (!empty($validated['related_to_type']) && !empty($validated['related_to_id'])) {
                $zohoData['What_Id'] = $validated['related_to_id'];
            }

            // Create in Zoho CRM first
            $zohoResponse = $this->crm->createCall($zohoData);

            if (isset($zohoResponse['data'][0]['details']['id'])) {
                $zohoCallId = $zohoResponse['data'][0]['details']['id'];

                // Save to local database
                $call = CrmCall::create([
                    'zoho_call_id' => $zohoCallId,
                    'subject' => $validated['subject'],
                    'call_type' => $validated['call_type'] ?? null,
                    'call_start_time' => $validated['call_start_time'] ?? null,
                    'call_duration' => $validated['call_duration'] ?? null,
                    'call_result' => $validated['call_result'] ?? null,
                    'call_purpose' => $validated['call_purpose'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'related_to_type' => $validated['related_to_type'] ?? null,
                    'related_to_id' => $validated['related_to_id'] ?? null,
                    'synced_to_zoho' => true,
                    'last_synced_at' => now(),
                ]);

                DB::commit();

                return redirect()->route('crm.calls.show', $call)
                    ->with('success', __('dashboard.call_created_successfully'));
            } else {
                throw new \Exception('Failed to create call in Zoho CRM: ' . json_encode($zohoResponse));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating call: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_call') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmCall $call)
    {
        return view('dashboard.crm.call.show', compact('call'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmCall $call)
    {
        return view('dashboard.crm.call.edit', compact('call'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmCall $call)
    {
        $validated = $request->validate([
            'subject' => 'required|string|max:255',
            'call_type' => 'nullable|string',
            'call_start_time' => 'nullable|date',
            'call_duration' => 'nullable|string',
            'call_result' => 'nullable|string',
            'call_purpose' => 'nullable|string',
            'description' => 'nullable|string',
            'related_to_type' => 'nullable|string|in:Leads,Contacts,Deals,Accounts',
            'related_to_id' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update in Zoho CRM if call has zoho_call_id
            if ($call->zoho_call_id) {
                // Prepare data for Zoho CRM
                $zohoData = [
                    'Subject' => $validated['subject'],
                ];

                // Add Call_Start_Time if provided
                // Zoho expects format: yyyy-MM-dd'T'HH:mm:ss+HH:mm or yyyy-MM-dd'T'HH:mm:ss.SSSZ
                if (!empty($validated['call_start_time'])) {
                    try {
                        $dateTime = new \DateTime($validated['call_start_time']);
                        // Try ISO 8601 format with timezone
                        $zohoData['Call_Start_Time'] = $dateTime->format('c'); // ISO 8601: 2025-11-12T18:30:00+03:00
                    } catch (\Exception $e) {
                        Log::warning('Invalid call_start_time format: ' . $validated['call_start_time']);
                    }
                }

                // Add optional fields
                if (!empty($validated['call_type'])) {
                    $zohoData['Call_Type'] = $validated['call_type'];
                }
                if (!empty($validated['call_duration'])) {
                    // Duration should be in minutes (integer or string like "30")
                    $zohoData['Call_Duration'] = $validated['call_duration'];
                }
                if (!empty($validated['call_result'])) {
                    $zohoData['Call_Result'] = $validated['call_result'];
                }
                if (!empty($validated['call_purpose'])) {
                    $zohoData['Call_Purpose'] = $validated['call_purpose'];
                }
                if (!empty($validated['description'])) {
                    $zohoData['Description'] = $validated['description'];
                }

                // Add related record if provided
                if (!empty($validated['related_to_type']) && !empty($validated['related_to_id'])) {
                    $zohoData['What_Id'] = $validated['related_to_id'];
                }

                // Update in Zoho CRM
                $this->crm->updateCall($call->zoho_call_id, $zohoData);
            }

            // Update in local database
            $call->update([
                'subject' => $validated['subject'],
                'call_type' => $validated['call_type'] ?? null,
                'call_start_time' => $validated['call_start_time'] ?? null,
                'call_duration' => $validated['call_duration'] ?? null,
                'call_result' => $validated['call_result'] ?? null,
                'call_purpose' => $validated['call_purpose'] ?? null,
                'description' => $validated['description'] ?? null,
                'related_to_type' => $validated['related_to_type'] ?? null,
                'related_to_id' => $validated['related_to_id'] ?? null,
                'last_synced_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('crm.calls.show', $call)
                ->with('success', __('dashboard.call_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating call: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_call') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmCall $call)
    {
        DB::beginTransaction();

        try {
            // Delete from Zoho CRM if call has zoho_call_id
            if ($call->zoho_call_id) {
                $this->crm->deleteCall($call->zoho_call_id);
            }

            // Delete from local database
            $call->delete();

            DB::commit();

            return redirect()->route('crm.calls.index')
                ->with('success', __('dashboard.call_deleted_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting call: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_call') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync calls from Zoho CRM
     */
    public function sync()
    {
        try {
            set_time_limit(0);

            // Run sync synchronously instead of dispatching to queue
            $job = new SyncCallsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));

            return response()->json([
                'success' => true,
                'message' => __('dashboard.calls_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing calls: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_calls') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
