<?php

namespace App\Console\Commands;

use App\Jobs\SyncItemsFromZoho;
use Illuminate\Console\Command;

class SyncItemsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'items:sync {--queue : Dispatch to queue instead of running synchronously}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync items from Zoho Books to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting items sync from Zoho Books...');

        if ($this->option('queue')) {
            // Dispatch to queue
            SyncItemsFromZoho::dispatch();
            $this->info('Items sync job dispatched to queue.');
        } else {
            // Run synchronously
            $job = new SyncItemsFromZoho();
            $job->handle(app(\App\Services\ZohoBooksService::class));
            $this->info('Items sync completed!');
        }

        return Command::SUCCESS;
    }
}
