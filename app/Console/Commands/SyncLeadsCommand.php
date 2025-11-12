<?php

namespace App\Console\Commands;

use App\Jobs\SyncLeadsFromZohoCRM;
use Illuminate\Console\Command;

class SyncLeadsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:sync-leads {--queue : Dispatch to queue instead of running synchronously}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync leads from Zoho CRM to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting leads synchronization from Zoho CRM...');

        if ($this->option('queue')) {
            SyncLeadsFromZohoCRM::dispatch();
            $this->info('Sync job dispatched to queue successfully!');
        } else {
            SyncLeadsFromZohoCRM::dispatchSync();
            $this->info('Leads synchronization completed successfully!');
        }

        return Command::SUCCESS;
    }
}
