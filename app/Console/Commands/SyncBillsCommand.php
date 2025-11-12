<?php

namespace App\Console\Commands;

use App\Jobs\SyncBillsFromZoho;
use Illuminate\Console\Command;

class SyncBillsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bills:sync {--queue : Dispatch to queue instead of running synchronously}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync bills from Zoho Books to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting bills synchronization from Zoho Books...');

        if ($this->option('queue')) {
            SyncBillsFromZoho::dispatch();
            $this->info('Sync job dispatched to queue successfully!');
        } else {
            try {
                SyncBillsFromZoho::dispatchSync();
                $this->info('Bill synchronization completed successfully!');
            } catch (\Exception $e) {
                $this->error('Error syncing bills: ' . $e->getMessage());
                return 1;
            }
        }

        return 0;
    }
}
