<?php

namespace App\Console\Commands;

use App\Jobs\SyncInvoicesFromZoho;
use Illuminate\Console\Command;

class SyncInvoicesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'invoices:sync {--queue : Dispatch the job to the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync invoices from Zoho Books to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting invoice synchronization from Zoho Books...');

        if ($this->option('queue')) {
            // Dispatch to queue
            SyncInvoicesFromZoho::dispatch();
            $this->info('Sync job dispatched to queue successfully!');
        } else {
            // Run synchronously
            try {
                SyncInvoicesFromZoho::dispatchSync();
                $this->info('Invoice synchronization completed successfully!');
            } catch (\Exception $e) {
                $this->error('Error syncing invoices: ' . $e->getMessage());
                return 1;
            }
        }

        return 0;
    }
}
