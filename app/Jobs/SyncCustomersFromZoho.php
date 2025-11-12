<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Services\ZohoBooksService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SyncCustomersFromZoho implements ShouldQueue
{
    use Queueable;

    protected $books;
    protected $syncedCount = 0;
    protected $updatedCount = 0;
    protected $errorCount = 0;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(ZohoBooksService $books): void
    {
        $this->books = $books;

        Log::info('Starting customer sync from Zoho Books');

        try {
            // Get all customers from Zoho Books
            $response = $this->books->getCustomers([
                'sort_column' => 'last_modified_time',
                'sort_order' => 'D',
            ]);

            $zohoCustomers = $response['contacts'] ?? [];

            Log::info('Found ' . count($zohoCustomers) . ' customers in Zoho Books');

            foreach ($zohoCustomers as $zohoCustomer) {
                try {
                    $this->syncCustomer($zohoCustomer);
                } catch (\Exception $e) {
                    $this->errorCount++;
                    Log::error('Error syncing customer: ' . $e->getMessage(), [
                        'customer_id' => $zohoCustomer['contact_id'] ?? 'unknown',
                        'customer_name' => $zohoCustomer['contact_name'] ?? 'unknown',
                    ]);
                }
            }

            Log::info('Customer sync completed', [
                'synced' => $this->syncedCount,
                'updated' => $this->updatedCount,
                'errors' => $this->errorCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Error starting customer sync: ' . $e->getMessage());
            throw $e;
        }
    }

    /**
     * Sync a single customer
     */
    protected function syncCustomer(array $zohoCustomer): void
    {
        DB::beginTransaction();

        try {
            $customerData = [
                'zoho_contact_id' => $zohoCustomer['contact_id'],
                'zoho_currency_id' => $zohoCustomer['currency_id'] ?? null,
                'contact_name' => $zohoCustomer['contact_name'],
                'company_name' => $zohoCustomer['company_name'] ?? null,
                'customer_name' => $zohoCustomer['customer_name'] ?? null,
                'vendor_name' => $zohoCustomer['vendor_name'] ?? null,
                'contact_type' => $this->mapContactType($zohoCustomer['contact_type'] ?? 'customer'),
                'customer_sub_type' => $zohoCustomer['customer_sub_type'] ?? 'business',
                'status' => $zohoCustomer['status'] ?? 'active',
                'first_name' => $zohoCustomer['first_name'] ?? null,
                'last_name' => $zohoCustomer['last_name'] ?? null,
                'email' => $zohoCustomer['email'] ?? null,
                'phone' => $zohoCustomer['phone'] ?? null,
                'mobile' => $zohoCustomer['mobile'] ?? null,
                'website' => $zohoCustomer['website'] ?? null,
                'twitter' => $zohoCustomer['twitter'] ?? null,
                'facebook' => $zohoCustomer['facebook'] ?? null,
                'currency_code' => $zohoCustomer['currency_code'] ?? 'SAR',
                'payment_terms' => $zohoCustomer['payment_terms'] ?? 0,
                'payment_terms_label' => $zohoCustomer['payment_terms_label'] ?? null,
                'outstanding_receivable_amount' => $zohoCustomer['outstanding_receivable_amount'] ?? 0,
                'outstanding_payable_amount' => $zohoCustomer['outstanding_payable_amount'] ?? 0,
                'unused_credits_receivable_amount' => $zohoCustomer['unused_credits_receivable_amount'] ?? 0,
                'unused_credits_payable_amount' => $zohoCustomer['unused_credits_payable_amount'] ?? 0,
                'portal_status' => $zohoCustomer['portal_status'] ?? 'disabled',
                'is_linked_with_zohocrm' => $zohoCustomer['is_linked_with_zohocrm'] ?? false,
                'source' => $zohoCustomer['source'] ?? 'user',
                'language_code' => $zohoCustomer['language_code'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ];

            // Find existing customer by Zoho ID
            $customer = Customer::where('zoho_contact_id', $zohoCustomer['contact_id'])->first();

            if ($customer) {
                // Update existing customer
                $customer->update($customerData);
                $this->updatedCount++;
                Log::info('Updated customer: ' . $customer->contact_name);
            } else {
                // Create new customer
                $customer = Customer::create($customerData);
                $this->syncedCount++;
                Log::info('Created customer: ' . $customer->contact_name);
            }

            DB::commit();

        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Map Zoho contact type to our enum
     */
    protected function mapContactType(string $type): string
    {
        return match($type) {
            'customer' => 'customer',
            'vendor' => 'vendor',
            default => 'customer',
        };
    }
}
