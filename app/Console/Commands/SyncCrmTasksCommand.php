<?php

namespace App\Console\Commands;

use App\Jobs\SyncTasksFromZohoCRM;
use Illuminate\Console\Command;

class SyncCrmTasksCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crm:sync-tasks {--queue : Run the sync job in the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync tasks from Zoho CRM to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting tasks synchronization from Zoho CRM...');

        if ($this->option('queue')) {
            SyncTasksFromZohoCRM::dispatch();
            $this->info('Tasks sync job has been queued.');
        } else {
            $job = new SyncTasksFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));
            $this->info('Tasks synchronization completed!');
        }

        return Command::SUCCESS;
    }
}
