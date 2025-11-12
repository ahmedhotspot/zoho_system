<?php

namespace App\Console\Commands;

use App\Jobs\SyncEstimatesFromZoho;
use Illuminate\Console\Command;

class SyncEstimatesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'estimates:sync {--queue : Dispatch the job to the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync estimates from Zoho Books to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting estimate synchronization from Zoho Books...');

        if ($this->option('queue')) {
            // Dispatch to queue
            SyncEstimatesFromZoho::dispatch();
            $this->info('Sync job dispatched to queue successfully!');
        } else {
            // Run synchronously
            try {
                SyncEstimatesFromZoho::dispatchSync();
                $this->info('Estimate synchronization completed successfully!');
            } catch (\Exception $e) {
                $this->error('Error syncing estimates: ' . $e->getMessage());
                return 1;
            }
        }

        return 0;
    }
}
