<?php

namespace App\Console\Commands;

use App\Jobs\SyncEventsFromZohoCRM;
use Illuminate\Console\Command;

class SyncCrmEventsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:sync-events {--queue : Run the sync job in the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync events from Zoho CRM to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting events synchronization from Zoho CRM...');

        if ($this->option('queue')) {
            SyncEventsFromZohoCRM::dispatch();
            $this->info('Events sync job has been queued.');
        } else {
            $job = new SyncEventsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));
            $this->info('Events synchronization completed!');
        }

        return Command::SUCCESS;
    }
}

