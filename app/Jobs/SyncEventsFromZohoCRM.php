<?php

namespace App\Jobs;

use App\Models\CrmEvent;
use App\Services\ZohoCRMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncEventsFromZohoCRM implements ShouldQueue
{
    use Queueable;

    protected $crm;
    protected $syncedCount = 0;
    protected $updatedCount = 0;
    protected $errorCount = 0;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ZohoCRMService $crm): void
    {
        $this->crm = $crm;

        Log::info('Starting events synchronization from Zoho CRM...');

        try {
            // Get last sync time
            $lastSyncTime = CrmEvent::max('last_synced_at');

            if ($lastSyncTime) {
                Log::info("Syncing events modified after: {$lastSyncTime}");
            } else {
                Log::info("First sync - fetching all events");
            }

            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $params = [
                    'page' => $page,
                    'per_page' => $perPage,
                    'sort_by' => 'Modified_Time',
                    'sort_order' => 'desc',
                ];

                // Add If-Modified-Since header if we have a last sync time
                if ($lastSyncTime) {
                    $params['modified_since'] = $lastSyncTime;
                }

                $response = $this->crm->getEvents($params);

                $zohoEvents = $response['data'] ?? [];
                $info = $response['info'] ?? [];

                foreach ($zohoEvents as $zohoEvent) {
                    $this->syncEvent($zohoEvent);
                }

                $hasMorePages = $info['more_records'] ?? false;
                $page++;

                // Safety break
                if ($page > 100) {
                    Log::warning('Reached maximum page limit (100) for events sync');
                    break;
                }
            }

            Log::info("Events sync completed. Synced: {$this->syncedCount}, Updated: {$this->updatedCount}, Errors: {$this->errorCount}");

        } catch (\Exception $e) {
            Log::error('Error syncing events from Zoho CRM: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncEvent(array $zohoEvent): void
    {
        DB::beginTransaction();

        try {
            $localEvent = CrmEvent::where('zoho_event_id', $zohoEvent['id'])->first();

            $eventData = [
                'zoho_event_id' => $zohoEvent['id'],
                'event_title' => $zohoEvent['Event_Title'] ?? null,
                'start_datetime' => isset($zohoEvent['Start_DateTime']) ? date('Y-m-d H:i:s', strtotime($zohoEvent['Start_DateTime'])) : null,
                'end_datetime' => isset($zohoEvent['End_DateTime']) ? date('Y-m-d H:i:s', strtotime($zohoEvent['End_DateTime'])) : null,
                'venue' => $zohoEvent['Venue'] ?? null,
                'location' => $zohoEvent['Location'] ?? null,

                // Related To (What_Id)
                'related_to_type' => $zohoEvent['What_Id']['module'] ?? null,
                'related_to_id' => $zohoEvent['What_Id']['id'] ?? null,
                'related_to_name' => $zohoEvent['What_Id']['name'] ?? null,

                // Participants
                'participants' => isset($zohoEvent['Participants']) ? json_encode($zohoEvent['Participants']) : null,
                'contact_name' => $zohoEvent['Contact_Name']['name'] ?? null,
                'contact_id' => $zohoEvent['Contact_Name']['id'] ?? null,

                // Owner
                'owner_id' => $zohoEvent['Owner']['id'] ?? null,
                'owner_name' => $zohoEvent['Owner']['name'] ?? null,

                // Additional Fields
                'description' => $zohoEvent['Description'] ?? null,
                'send_notification' => $zohoEvent['Send_Notification'] ?? false,
                'reminder' => $zohoEvent['Remind_At'] ?? null,
                'check_in_status' => $zohoEvent['$check_in_status'] ?? false,
                'check_in_address' => $zohoEvent['$check_in_address'] ?? null,
                'check_in_time' => isset($zohoEvent['$check_in_time']) ? date('Y-m-d H:i:s', strtotime($zohoEvent['$check_in_time'])) : null,
                'check_in_sub_locality' => $zohoEvent['$check_in_sub_locality'] ?? null,
                'check_in_city' => $zohoEvent['$check_in_city'] ?? null,
                'check_in_state' => $zohoEvent['$check_in_state'] ?? null,
                'check_in_country' => $zohoEvent['$check_in_country'] ?? null,

                // Recurring
                'is_recurring' => isset($zohoEvent['$recurring_activity']) && $zohoEvent['$recurring_activity'] ? true : false,
                'recurring_activity' => $zohoEvent['$recurring_activity'] ?? null,

                // Zoho Timestamps
                'zoho_created_time' => isset($zohoEvent['Created_Time']) ? date('Y-m-d H:i:s', strtotime($zohoEvent['Created_Time'])) : null,
                'zoho_modified_time' => isset($zohoEvent['Modified_Time']) ? date('Y-m-d H:i:s', strtotime($zohoEvent['Modified_Time'])) : null,
                'last_synced_at' => now(),
            ];

            if ($localEvent) {
                $localEvent->update($eventData);
                $this->updatedCount++;
                Log::info("Updated event: {$eventData['event_title']} (ID: {$zohoEvent['id']})");
            } else {
                CrmEvent::create($eventData);
                $this->syncedCount++;
                Log::info("Created new event: {$eventData['event_title']} (ID: {$zohoEvent['id']})");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error("Error syncing event {$zohoEvent['id']}: " . $e->getMessage());
        }
    }
}
