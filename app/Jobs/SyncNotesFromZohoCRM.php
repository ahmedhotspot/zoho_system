<?php

namespace App\Jobs;

use App\Models\CrmNote;
use App\Services\ZohoCRMService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncNotesFromZohoCRM implements ShouldQueue
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

        Log::info('Starting notes synchronization from Zoho CRM...');

        try {
            // Sync notes from different modules
            $modules = ['Leads', 'Contacts', 'Deals', 'Accounts'];

            foreach ($modules as $module) {
                $this->syncNotesForModule($module);
            }

            Log::info("Notes sync completed. Synced: {$this->syncedCount}, Updated: {$this->updatedCount}, Errors: {$this->errorCount}");

        } catch (\Exception $e) {
            Log::error('Error syncing notes from Zoho CRM: ' . $e->getMessage());
            throw $e;
        }
    }

    protected function syncNotesForModule(string $module): void
    {
        try {
            Log::info("Syncing notes for {$module}...");

            // Get all records from this module
            $records = $this->getRecordsForModule($module);

            foreach ($records as $record) {
                $this->syncNotesForRecord($module, $record['id'], $record['name'] ?? null);
            }

        } catch (\Exception $e) {
            Log::error("Error syncing notes for {$module}: " . $e->getMessage());
        }
    }

    protected function getRecordsForModule(string $module): array
    {
        $allRecords = [];
        $page = 1;
        $hasMorePages = true;

        while ($hasMorePages && $page <= 10) { // Limit to 10 pages per module
            try {
                // Use the appropriate method for each module
                $response = match($module) {
                    'Leads' => $this->crm->getLeads(['page' => $page, 'per_page' => 200]),
                    'Contacts' => $this->crm->getContacts(['page' => $page, 'per_page' => 200]),
                    'Deals' => $this->crm->getDeals(['page' => $page, 'per_page' => 200]),
                    'Accounts' => $this->crm->getAccounts(['page' => $page, 'per_page' => 200]),
                    default => ['data' => [], 'info' => ['more_records' => false]]
                };

                $records = $response['data'] ?? [];

                // Extract only id and name fields
                $records = array_map(function($record) use ($module) {
                    return [
                        'id' => $record['id'] ?? null,
                        'name' => match($module) {
                            'Leads' => ($record['First_Name'] ?? '') . ' ' . ($record['Last_Name'] ?? ''),
                            'Contacts' => ($record['First_Name'] ?? '') . ' ' . ($record['Last_Name'] ?? ''),
                            'Deals' => $record['Deal_Name'] ?? null,
                            'Accounts' => $record['Account_Name'] ?? null,
                            default => null
                        }
                    ];
                }, $records);

                $allRecords = array_merge($allRecords, $records);

                $hasMorePages = $response['info']['more_records'] ?? false;
                $page++;

            } catch (\Exception $e) {
                Log::error("Error fetching {$module} records: " . $e->getMessage());
                break;
            }
        }

        return $allRecords;
    }

    protected function syncNotesForRecord(string $module, string $recordId, ?string $recordName): void
    {
        try {
            $response = $this->crm->getNotes($module, $recordId);

            $zohoNotes = $response['data'] ?? [];

            foreach ($zohoNotes as $zohoNote) {
                $this->syncNote($zohoNote, $module, $recordId, $recordName);
            }

        } catch (\Exception $e) {
            // Silently skip if no notes found for this record
            if (!str_contains($e->getMessage(), 'NO_DATA')) {
                Log::debug("Error syncing notes for {$module} {$recordId}: " . $e->getMessage());
            }
        }
    }

    protected function syncNote(array $zohoNote, string $module, string $recordId, ?string $recordName): void
    {
        DB::beginTransaction();

        try {
            $localNote = CrmNote::where('zoho_note_id', $zohoNote['id'])->first();

            $noteData = [
                'zoho_note_id' => $zohoNote['id'],
                'note_title' => $zohoNote['Note_Title'] ?? null,
                'note_content' => $zohoNote['Note_Content'] ?? null,
                'parent_module' => $module,
                'parent_id' => $recordId,
                'parent_name' => $recordName ?? ($zohoNote['Parent_Id']['name'] ?? null),
                'owner_id' => $zohoNote['Owner']['id'] ?? null,
                'owner_name' => $zohoNote['Owner']['name'] ?? null,
                'created_by_id' => $zohoNote['Created_By']['id'] ?? null,
                'created_by_name' => $zohoNote['Created_By']['name'] ?? null,
                'modified_by_id' => $zohoNote['Modified_By']['id'] ?? null,
                'modified_by_name' => $zohoNote['Modified_By']['name'] ?? null,
                'zoho_created_time' => isset($zohoNote['Created_Time']) ? date('Y-m-d H:i:s', strtotime($zohoNote['Created_Time'])) : null,
                'zoho_modified_time' => isset($zohoNote['Modified_Time']) ? date('Y-m-d H:i:s', strtotime($zohoNote['Modified_Time'])) : null,
                'last_synced_at' => now(),
            ];

            if ($localNote) {
                $localNote->update($noteData);
                $this->updatedCount++;
            } else {
                CrmNote::create($noteData);
                $this->syncedCount++;
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            $this->errorCount++;
            Log::error('Error syncing note: ' . $e->getMessage(), [
                'note_id' => $zohoNote['id'] ?? 'unknown',
                'parent_module' => $module,
                'parent_id' => $recordId,
            ]);
        }
    }
}
