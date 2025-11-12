<?php

namespace App\Console\Commands;

use App\Jobs\SyncAccountsFromZoho;
use Illuminate\Console\Command;

class SyncAccountsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'accounts:sync {--queue : Dispatch to queue instead of running synchronously}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync accounts from Zoho Books to local database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting account synchronization from Zoho Books...');

        if ($this->option('queue')) {
            SyncAccountsFromZoho::dispatch();
            $this->info('Sync job dispatched to queue successfully!');
        } else {
            SyncAccountsFromZoho::dispatchSync();
            $this->info('Account synchronization completed successfully!');
        }

        return Command::SUCCESS;
    }
}
