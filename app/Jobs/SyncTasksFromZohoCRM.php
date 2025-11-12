<?php

namespace App\Jobs;

use App\Models\CrmTask;
use App\Services\ZohoCRMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncTasksFromZohoCRM implements ShouldQueue
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

        Log::info('Starting tasks synchronization from Zoho CRM...');

        try {
            $page = 1;
            $perPage = 200;
            $hasMorePages = true;

            while ($hasMorePages) {
                $response = $this->crm->getTasks([
                    'page' => $page,
                    'per_page' => $perPage,
                ]);

                $zohoTasks = $response['data'] ?? [];
                $info = $response['info'] ?? [];

                foreach ($zohoTasks as $zohoTask) {
                    $this->syncTask($zohoTask);
                }

                $hasMorePages = $info['more_records'] ?? false;
                $page++;

                // Safety break
                if ($page > 100) {
                    Log::warning('Reached maximum page limit (100) for tasks sync');
                    break;
                }
            }

            Log::info("Tasks sync completed. Synced: {$this->syncedCount}, Updated: {$this->updatedCount}, Errors: {$this->errorCount}");

        } catch (\Exception $e) {
            Log::error('Error syncing tasks from Zoho CRM: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncTask(array $zohoTask): void
    {
        DB::beginTransaction();

        try {
            $localTask = CrmTask::where('zoho_task_id', $zohoTask['id'])->first();

            $taskData = [
                'zoho_task_id' => $zohoTask['id'],
                'subject' => $zohoTask['Subject'] ?? null,
                'due_date' => $zohoTask['Due_Date'] ?? null,
                'status' => $zohoTask['Status'] ?? null,
                'priority' => $zohoTask['Priority'] ?? null,
                'related_to_type' => $zohoTask['What_Id']['module'] ?? null,
                'related_to_id' => $zohoTask['What_Id']['id'] ?? null,
                'related_to_name' => $zohoTask['What_Id']['name'] ?? null,
                'contact_id' => $zohoTask['Who_Id']['id'] ?? null,
                'contact_name' => $zohoTask['Who_Id']['name'] ?? null,
                'owner_id' => $zohoTask['Owner']['id'] ?? null,
                'owner_name' => $zohoTask['Owner']['name'] ?? null,
                'description' => $zohoTask['Description'] ?? null,
                'send_notification_email' => $zohoTask['Send_Notification_Email'] ?? false,
                'reminder' => $zohoTask['Reminder'] ?? null,
                'repeat' => $zohoTask['Repeat'] ?? false,
                'zoho_created_time' => isset($zohoTask['Created_Time']) ? date('Y-m-d H:i:s', strtotime($zohoTask['Created_Time'])) : null,
                'zoho_modified_time' => isset($zohoTask['Modified_Time']) ? date('Y-m-d H:i:s', strtotime($zohoTask['Modified_Time'])) : null,
                'closed_time' => isset($zohoTask['Closed_Time']) ? date('Y-m-d H:i:s', strtotime($zohoTask['Closed_Time'])) : null,
                'last_synced_at' => now(),
            ];

            if ($localTask) {
                $localTask->update($taskData);
                $this->updatedCount++;
                Log::info("Updated task: {$taskData['subject']} (ID: {$zohoTask['id']})");
            } else {
                CrmTask::create($taskData);
                $this->syncedCount++;
                Log::info("Created new task: {$taskData['subject']} (ID: {$zohoTask['id']})");
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error("Error syncing task {$zohoTask['id']}: " . $e->getMessage());
        }
    }
}
