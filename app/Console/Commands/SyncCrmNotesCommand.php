<?php

namespace App\Console\Commands;

use App\Jobs\SyncNotesFromZohoCRM;
use Illuminate\Console\Command;

class SyncCrmNotesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:sync-notes {--queue : Run the sync job in the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync notes from Zoho CRM to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting notes synchronization from Zoho CRM...');

        if ($this->option('queue')) {
            SyncNotesFromZohoCRM::dispatch();
            $this->info('Notes sync job has been queued.');
        } else {
            $job = new SyncNotesFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));
            $this->info('Notes synchronization completed!');
        }

        return Command::SUCCESS;
    }
}
