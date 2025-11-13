<?php

namespace App\Http\Controllers;

use App\Models\CrmEvent;
use App\Jobs\SyncEventsFromZohoCRM;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrmEventController extends Controller
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
        $query = CrmEvent::query();

        // Search
        if ($request->filled('search')) {
            $query->search($request->search);
        }

        // Filter by status (upcoming/past/today)
        if ($request->filled('status')) {
            switch ($request->status) {
                case 'upcoming':
                    $query->upcoming();
                    break;
                case 'past':
                    $query->past();
                    break;
                case 'today':
                    $query->today();
                    break;
            }
        }

        $events = $query->orderBy('start_datetime', 'desc')->paginate(20);

        return view('dashboard.crm.event.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('dashboard.crm.event.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'event_title' => 'required|string|max:255',
            'start_datetime' => 'nullable|date',
            'end_datetime' => 'nullable|date|after:start_datetime',
            'venue' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'related_to_type' => 'nullable|string|in:Leads,Contacts,Deals,Accounts',
            'related_to_id' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Prepare data for Zoho CRM
            $zohoData = [
                'Event_Title' => $validated['event_title'],
            ];

            // Add Start_DateTime if provided
            if (!empty($validated['start_datetime'])) {
                $zohoData['Start_DateTime'] = date('Y-m-d\TH:i:sP', strtotime($validated['start_datetime']));
            }

            // Add End_DateTime if provided and ensure it's after Start_DateTime
            if (!empty($validated['end_datetime'])) {
                $endDateTime = strtotime($validated['end_datetime']);
                $startDateTime = !empty($validated['start_datetime']) ? strtotime($validated['start_datetime']) : null;

                // Validate that end is after start
                if ($startDateTime && $endDateTime <= $startDateTime) {
                    throw new \Exception(__('dashboard.end_datetime_must_be_after_start'));
                }

                $zohoData['End_DateTime'] = date('Y-m-d\TH:i:sP', $endDateTime);
            }

            // Add optional fields
            if (!empty($validated['venue'])) {
                $zohoData['Venue'] = $validated['venue'];
            }
            if (!empty($validated['location'])) {
                $zohoData['Location'] = $validated['location'];
            }
            if (!empty($validated['description'])) {
                $zohoData['Description'] = $validated['description'];
            }

            // Add related record if provided
            if (!empty($validated['related_to_type']) && !empty($validated['related_to_id'])) {
                $zohoData['What_Id'] = $validated['related_to_id'];
            }

            // Create in Zoho CRM first
            $zohoResponse = $this->crm->createEvent($zohoData);

            if (isset($zohoResponse['data'][0]['details']['id'])) {
                $zohoEventId = $zohoResponse['data'][0]['details']['id'];

                // Save to local database
                $event = CrmEvent::create([
                    'zoho_event_id' => $zohoEventId,
                    'event_title' => $validated['event_title'],
                    'start_datetime' => $validated['start_datetime'],
                    'end_datetime' => $validated['end_datetime'],
                    'venue' => $validated['venue'] ?? null,
                    'location' => $validated['location'] ?? null,
                    'description' => $validated['description'] ?? null,
                    'related_to_type' => $validated['related_to_type'] ?? null,
                    'related_to_id' => $validated['related_to_id'] ?? null,
                    'synced_to_zoho' => true,
                    'last_synced_at' => now(),
                ]);

                DB::commit();

                return redirect()->route('crm.events.show', $event)
                    ->with('success', __('dashboard.event_created_successfully'));
            } else {
                throw new \Exception('Failed to create event in Zoho CRM: ' . json_encode($zohoResponse));
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating event: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_event') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CrmEvent $event)
    {
        return view('dashboard.crm.event.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CrmEvent $event)
    {
        return view('dashboard.crm.event.edit', compact('event'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CrmEvent $event)
    {
        $validated = $request->validate([
            'event_title' => 'required|string|max:255',
            'start_datetime' => 'nullable|date',
            'end_datetime' => 'nullable|date|after:start_datetime',
            'venue' => 'nullable|string|max:255',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'related_to_type' => 'nullable|string|in:Leads,Contacts,Deals,Accounts',
            'related_to_id' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update in Zoho CRM if event has zoho_event_id
            if ($event->zoho_event_id) {
                // Prepare data for Zoho CRM
                $zohoData = [
                    'Event_Title' => $validated['event_title'],
                ];

                // Add Start_DateTime if provided
                if (!empty($validated['start_datetime'])) {
                    $zohoData['Start_DateTime'] = date('Y-m-d\TH:i:sP', strtotime($validated['start_datetime']));
                }

                // Add End_DateTime if provided and ensure it's after Start_DateTime
                if (!empty($validated['end_datetime'])) {
                    $endDateTime = strtotime($validated['end_datetime']);
                    $startDateTime = !empty($validated['start_datetime']) ? strtotime($validated['start_datetime']) : null;

                    // Validate that end is after start
                    if ($startDateTime && $endDateTime <= $startDateTime) {
                        throw new \Exception(__('dashboard.end_datetime_must_be_after_start'));
                    }

                    $zohoData['End_DateTime'] = date('Y-m-d\TH:i:sP', $endDateTime);
                }

                // Add optional fields
                if (!empty($validated['venue'])) {
                    $zohoData['Venue'] = $validated['venue'];
                }
                if (!empty($validated['location'])) {
                    $zohoData['Location'] = $validated['location'];
                }
                if (!empty($validated['description'])) {
                    $zohoData['Description'] = $validated['description'];
                }

                // Add related record if provided
                if (!empty($validated['related_to_type']) && !empty($validated['related_to_id'])) {
                    $zohoData['What_Id'] = $validated['related_to_id'];
                }

                // Update in Zoho CRM
                $zohoResponse = $this->crm->updateEvent($event->zoho_event_id, $zohoData);

                if (!isset($zohoResponse['data'][0]['code']) || $zohoResponse['data'][0]['code'] !== 'SUCCESS') {
                    throw new \Exception('Failed to update event in Zoho CRM: ' . json_encode($zohoResponse));
                }
            }

            // Update in local database
            $event->update([
                'event_title' => $validated['event_title'],
                'start_datetime' => $validated['start_datetime'],
                'end_datetime' => $validated['end_datetime'],
                'venue' => $validated['venue'] ?? null,
                'location' => $validated['location'] ?? null,
                'description' => $validated['description'] ?? null,
                'related_to_type' => $validated['related_to_type'] ?? null,
                'related_to_id' => $validated['related_to_id'] ?? null,
                'last_synced_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('crm.events.show', $event)
                ->with('success', __('dashboard.event_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating event: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_event') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CrmEvent $event)
    {
        DB::beginTransaction();

        try {
            // Delete from Zoho CRM if event has zoho_event_id
            if ($event->zoho_event_id) {
                $zohoResponse = $this->crm->deleteEvent($event->zoho_event_id);

                // Check if deletion was successful
                if (!isset($zohoResponse['data'][0]['code']) || $zohoResponse['data'][0]['code'] !== 'SUCCESS') {
                    throw new \Exception('Failed to delete event from Zoho CRM: ' . json_encode($zohoResponse));
                }
            }

            // Delete from local database
            $event->delete();

            DB::commit();

            return redirect()->route('crm.events.index')
                ->with('success', __('dashboard.event_deleted_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting event: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_event') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync events from Zoho CRM
     */
    public function sync()
    {
        try {
            set_time_limit(0);

            $job = new SyncEventsFromZohoCRM();
            $job->handle($this->crm);

            return response()->json([
                'success' => true,
                'message' => __('dashboard.events_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing events: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_events') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
