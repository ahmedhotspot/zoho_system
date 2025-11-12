<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Jobs\SyncCustomersFromZoho;
use App\Services\ZohoBooksService;

class SyncCustomersCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'customers:sync {--queue : Dispatch the job to the queue}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync customers from Zoho Books to local database';

    /**
     * Execute the console command.
     */
    public function handle(ZohoBooksService $books)
    {
        $this->info('Starting customer synchronization from Zoho Books...');

        if ($this->option('queue')) {
            // Dispatch to queue
            SyncCustomersFromZoho::dispatch();
            $this->info('Customer sync job dispatched to queue.');
        } else {
            // Run synchronously
            $job = new SyncCustomersFromZoho();
            $job->handle($books);
            $this->info('Customer synchronization completed!');
        }

        return Command::SUCCESS;
    }
}
