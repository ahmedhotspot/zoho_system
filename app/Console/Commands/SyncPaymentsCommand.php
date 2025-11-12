<?php

namespace App\Console\Commands;

use App\Jobs\SyncPaymentsFromZoho;
use Illuminate\Console\Command;

class SyncPaymentsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'payments:sync {--queue : Dispatch to queue instead of running synchronously}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync payments from Zoho Books to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting payments sync from Zoho Books...');

        if ($this->option('queue')) {
            // Dispatch to queue
            SyncPaymentsFromZoho::dispatch();
            $this->info('Payments sync job dispatched to queue.');
        } else {
            // Run synchronously
            $job = new SyncPaymentsFromZoho();
            $job->handle(app(\App\Services\ZohoBooksService::class));
            $this->info('Payments sync completed!');
        }

        return Command::SUCCESS;
    }
}
