<?php

namespace App\Console\Commands;

use App\Jobs\SyncCallsFromZohoCRM;
use Illuminate\Console\Command;

class SyncCrmCallsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:sync-calls {--queue : Run the sync job in the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync calls from Zoho CRM to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting calls synchronization from Zoho CRM...');

        if ($this->option('queue')) {
            SyncCallsFromZohoCRM::dispatch();
            $this->info('Calls sync job has been queued.');
        } else {
            $job = new SyncCallsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));
            $this->info('Calls synchronization completed!');
        }

        return Command::SUCCESS;
    }
}
