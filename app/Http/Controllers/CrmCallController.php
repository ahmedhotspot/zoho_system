<?php

namespace App\Http\Controllers;

use App\Jobs\SyncCallsFromZohoCRM;
use App\Models\CrmCall;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
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
            'description' => 'nullable|string',
        ]);

        try {
            // Create in Zoho CRM first
            $zohoData = [
                'Subject' => $validated['subject'],
            ];

            if (!empty($validated['call_type'])) {
                $zohoData['Call_Type'] = $validated['call_type'];
            }
            if (!empty($validated['call_start_time'])) {
                // Convert to Zoho CRM datetime format: YYYY-MM-DDTHH:MM:SS (without timezone)
                $dt = new \DateTime($validated['call_start_time']);
                $zohoData['Call_Start_Time'] = $dt->format('Y-m-d\TH:i:s');
            }
            if (!empty($validated['call_duration'])) {
                $zohoData['Call_Duration'] = $validated['call_duration'];
            }
            if (!empty($validated['call_result'])) {
                $zohoData['Call_Result'] = $validated['call_result'];
            }
            if (!empty($validated['description'])) {
                $zohoData['Description'] = $validated['description'];
            }

            $response = $this->crm->createCall($zohoData);

            if (isset($response['data'][0]['details']['id'])) {
                $zohoCallId = $response['data'][0]['details']['id'];

                // Create in local database
                $validated['zoho_call_id'] = $zohoCallId;
                $validated['last_synced_at'] = now();
                CrmCall::create($validated);

                return redirect()->route('crm.calls.index')
                    ->with('success', __('dashboard.call_created_successfully'));
            }

            throw new \Exception('Failed to get Zoho call ID from response');

        } catch (\Exception $e) {
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
            'description' => 'nullable|string',
        ]);

        try {
            // Update in Zoho CRM if synced
            if ($call->zoho_call_id) {
                $zohoData = [
                    'Subject' => $validated['subject'],
                ];

                if (!empty($validated['call_type'])) {
                    $zohoData['Call_Type'] = $validated['call_type'];
                }
                if (!empty($validated['call_start_time'])) {
                    // Convert to Zoho CRM datetime format: YYYY-MM-DDTHH:MM:SS (without timezone)
                    $dt = new \DateTime($validated['call_start_time']);
                    $zohoData['Call_Start_Time'] = $dt->format('Y-m-d\TH:i:s');
                }
                if (!empty($validated['call_duration'])) {
                    $zohoData['Call_Duration'] = $validated['call_duration'];
                }
                if (!empty($validated['call_result'])) {
                    $zohoData['Call_Result'] = $validated['call_result'];
                }
                if (!empty($validated['description'])) {
                    $zohoData['Description'] = $validated['description'];
                }

                $this->crm->updateCall($call->zoho_call_id, $zohoData);
                $validated['last_synced_at'] = now();
            }

            // Update in local database
            $call->update($validated);

            return redirect()->route('crm.calls.index')
                ->with('success', __('dashboard.call_updated_successfully'));

        } catch (\Exception $e) {
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
        try {
            // Delete from Zoho CRM if synced
            if ($call->zoho_call_id) {
                $this->crm->deleteCall($call->zoho_call_id);
            }

            // Delete from local database
            $call->delete();

            return redirect()->route('crm.calls.index')
                ->with('success', __('dashboard.call_deleted_successfully'));

        } catch (\Exception $e) {
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
