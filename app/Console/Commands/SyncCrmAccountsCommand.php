<?php

namespace App\Console\Commands;

use App\Jobs\SyncAccountsFromZohoCRM;
use Illuminate\Console\Command;

class SyncCrmAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:sync-accounts {--queue : Run the sync job in the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync accounts from Zoho CRM to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting accounts synchronization from Zoho CRM...');

        if ($this->option('queue')) {
            SyncAccountsFromZohoCRM::dispatch();
            $this->info('Accounts sync job has been queued.');
        } else {
            $job = new SyncAccountsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));
            $this->info('Accounts synchronization completed!');
        }

        return Command::SUCCESS;
    }
}
