<?php

namespace App\Console\Commands;

use App\Jobs\SyncContactsFromZohoCRM;
use Illuminate\Console\Command;

class SyncContactsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:sync-contacts {--queue : Run the sync job in the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync contacts from Zoho CRM to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting contacts synchronization from Zoho CRM...');

        if ($this->option('queue')) {
            SyncContactsFromZohoCRM::dispatch();
            $this->info('Contacts sync job has been queued.');
        } else {
            $job = new SyncContactsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));
            $this->info('Contacts synchronization completed!');
        }

        return Command::SUCCESS;
    }
}
