<?php

namespace App\Console\Commands;

use App\Jobs\SyncExpensesFromZoho;
use Illuminate\Console\Command;

class SyncExpensesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'expenses:sync {--queue : Dispatch to queue instead of running synchronously}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync expenses from Zoho Books';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting expense synchronization from Zoho Books...');

        if ($this->option('queue')) {
            // Dispatch to queue
            SyncExpensesFromZoho::dispatch();
            $this->info('Sync job dispatched to queue successfully!');
        } else {
            // Run synchronously
            try {
                SyncExpensesFromZoho::dispatchSync();
                $this->info('Expense synchronization completed successfully!');
            } catch (\Exception $e) {
                $this->error('Error syncing expenses: ' . $e->getMessage());
                return 1;
            }
        }

        return 0;
    }
}
