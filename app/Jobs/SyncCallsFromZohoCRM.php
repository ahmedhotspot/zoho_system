<?php

namespace App\Jobs;

use App\Models\CrmCall;
use App\Services\ZohoCRMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncCallsFromZohoCRM implements ShouldQueue
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

        Log::info('Starting calls synchronization from Zoho CRM...');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->crm->getCalls([
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoCalls = $response['data'] ?? [];
                $info = $response['info'] ?? [];

                foreach ($zohoCalls as $zohoCall) {
                    $this->syncCall($zohoCall);
                }

                $hasMorePages = $info['more_records'] ?? false;
                $page++;

                // Safety break
                if ($page > 100) {
                    Log::warning('Reached maximum page limit (100) for calls sync');
                    break;
                }
            }

            Log::info("Calls sync completed. Synced: {$this->syncedCount}, Updated: {$this->updatedCount}, Errors: {$this->errorCount}");

        } catch (\Exception $e) {
            Log::error('Error syncing calls from Zoho CRM: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncCall(array $zohoCall): void
    {
        DB::beginTransaction();

        try {
            $localCall = CrmCall::where('zoho_call_id', $zohoCall['id'])->first();

            // Convert call_duration from HH:MM format to minutes
            $callDuration = null;
            if (isset($zohoCall['Call_Duration'])) {
                $duration = $zohoCall['Call_Duration'];
                // Check if it's in HH:MM format (e.g., "00:40")
                if (preg_match('/^(\d{1,2}):(\d{2})$/', $duration, $matches)) {
                    $hours = (int) $matches[1];
                    $minutes = (int) $matches[2];
                    $callDuration = ($hours * 60) + $minutes;
                } else {
                    // If it's already a number, use it as is
                    $callDuration = is_numeric($duration) ? (int) $duration : null;
                }
            }

            $callData = [
                'zoho_call_id' => $zohoCall['id'],
                'subject' => $zohoCall['Subject'] ?? null,
                'call_type' => $zohoCall['Call_Type'] ?? null,
                'call_purpose' => $zohoCall['Call_Purpose'] ?? null,
                'call_start_time' => isset($zohoCall['Call_Start_Time']) ? date('Y-m-d H:i:s', strtotime($zohoCall['Call_Start_Time'])) : null,
                'call_duration' => $callDuration,
                'call_result' => $zohoCall['Call_Result'] ?? null,
                'related_to_type' => $zohoCall['What_Id']['module'] ?? null,
                'related_to_id' => $zohoCall['What_Id']['id'] ?? null,
                'related_to_name' => $zohoCall['What_Id']['name'] ?? null,
                'who_id_type' => $zohoCall['Who_Id']['module'] ?? null,
                'who_id' => $zohoCall['Who_Id']['id'] ?? null,
                'who_name' => $zohoCall['Who_Id']['name'] ?? null,
                'owner_id' => $zohoCall['Owner']['id'] ?? null,
                'owner_name' => $zohoCall['Owner']['name'] ?? null,
                'description' => $zohoCall['Description'] ?? null,
                'call_agenda' => $zohoCall['Call_Agenda'] ?? null,
                'voice_recording' => $zohoCall['Voice_Recording'] ?? null,
                'outgoing_call_status' => $zohoCall['Outgoing_Call_Status'] ?? null,
                'caller_id' => $zohoCall['Caller_ID'] ?? null,
                'dialled_number' => $zohoCall['Dialled_Number'] ?? null,
                'zoho_created_time' => isset($zohoCall['Created_Time']) ? date('Y-m-d H:i:s', strtotime($zohoCall['Created_Time'])) : null,
                'zoho_modified_time' => isset($zohoCall['Modified_Time']) ? date('Y-m-d H:i:s', strtotime($zohoCall['Modified_Time'])) : null,
                'last_synced_at' => now(),
            ];

            if ($localCall) {
                $localCall->update($callData);
                $this->updatedCount++;
                Log::info("Updated call: {$callData['subject']} (ID: {$zohoCall['id']})");
            } else {
                CrmCall::create($callData);
                $this->syncedCount++;
                Log::info("Created new call: {$callData['subject']} (ID: {$zohoCall['id']})");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error("Error syncing call {$zohoCall['id']}: " . $e->getMessage());
        }
    }
}
