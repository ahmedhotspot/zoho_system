<?php

namespace App\Console\Commands;

use App\Jobs\SyncDealsFromZohoCRM;
use Illuminate\Console\Command;

class SyncDealsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:sync-deals {--queue : Run the sync job in the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync deals from Zoho CRM';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting deals synchronization from Zoho CRM...');

        if ($this->option('queue')) {
            SyncDealsFromZohoCRM::dispatch();
            $this->info('Deals sync job has been queued.');
        } else {
            $job = new SyncDealsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));
            $this->info('Deals synchronization completed!');
        }

        return Command::SUCCESS;
    }
}
