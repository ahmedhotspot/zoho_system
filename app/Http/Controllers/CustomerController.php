<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ZohoBooksService;
use Illuminate\Support\Facades\Log;
use App\Models\Customer;

class CustomerController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    /**
     * Display a listing of customers
     */
    public function index(Request $request)
    {
        try {
            // Get customers from local database
            $query = Customer::orderBy('contact_name', 'asc');

            // Apply filters
            if ($request->filled('status')) {
                Log::info('Filtering by status: ' . $request->status);
                $query->where('status', $request->status);
            }

            if ($request->filled('contact_type')) {
                Log::info('Filtering by contact_type: ' . $request->contact_type);
                $query->where('contact_type', 'like', "%{$request->contact_type}%");
            }

            if ($request->filled('search')) {
                $search = $request->search;
                Log::info('Searching for: ' . $search);
                $query->where(function($q) use ($search) {
                    $q->where('contact_name', 'like', "%{$search}%")
                      ->orWhere('company_name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $customers = $query->paginate($perPage);

            Log::info('Total customers found: ' . $customers->total());

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'data' => $customers
                ]);
            }

            return view('dashboard.customer.index', compact('customers'));

        } catch (\Exception $e) {
            Log::error('Error fetching customers: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching customers'
                ], 500);
            }

            return back()->with('error', 'Error fetching customers: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new customer
     */
    public function create()
    {
        return view('dashboard.customer.create');
    }

    /**
     * Store a newly created customer
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'contact_name' => 'required|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'contact_type' => 'required|in:customer,vendor,both',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'mobile' => 'nullable|string|max:50',
                'website' => 'nullable|url|max:255',
            ]);

            // Create customer in Zoho Books first
            $zohoCustomer = $this->books->createContact($validated);

            // Then save to local database
            $customer = Customer::create([
                'zoho_contact_id' => $zohoCustomer['contact_id'],
                'contact_name' => $zohoCustomer['contact_name'],
                'company_name' => $zohoCustomer['company_name'] ?? null,
                'contact_type' => $zohoCustomer['contact_type'],
                'email' => $zohoCustomer['email'] ?? null,
                'phone' => $zohoCustomer['phone'] ?? null,
                'mobile' => $zohoCustomer['mobile'] ?? null,
                'website' => $zohoCustomer['website'] ?? null,
                'status' => $zohoCustomer['status'] ?? 'active',
                'synced_to_zoho' => true,
            ]);

            return redirect()->route('customers.index')
                ->with('success', __('dashboard.customer_created_successfully'));

        } catch (\Exception $e) {
            Log::error('Error creating customer: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_customer') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified customer
     */
    public function show(Customer $customer)
    {
        // Load invoices relationship
        $customer->load('invoices');

        return view('dashboard.customer.show', compact('customer'));
    }

    /**
     * Show the form for editing the specified customer
     */
    public function edit(Customer $customer)
    {
        return view('dashboard.customer.edit', compact('customer'));
    }

    /**
     * Update the specified customer
     */
    public function update(Request $request, Customer $customer)
    {
        try {
            $validated = $request->validate([
                'contact_name' => 'required|string|max:255',
                'company_name' => 'nullable|string|max:255',
                'contact_type' => 'required|in:customer,vendor,both',
                'email' => 'nullable|email|max:255',
                'phone' => 'nullable|string|max:50',
                'mobile' => 'nullable|string|max:50',
                'website' => 'nullable|url|max:255',
            ]);

            // Update in Zoho Books first
            if ($customer->zoho_contact_id) {
                $this->books->updateContact($customer->zoho_contact_id, $validated);
            }

            // Then update local database
            $customer->update($validated);

            return redirect()->route('customers.show', $customer->id)
                ->with('success', __('dashboard.customer_updated_successfully'));

        } catch (\Exception $e) {
            Log::error('Error updating customer: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_customer') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified customer
     */
    public function destroy(Customer $customer)
    {
        try {
            // Delete from Zoho Books first
            if ($customer->zoho_contact_id) {
                $this->books->deleteContact($customer->zoho_contact_id);
            }

            // Then delete from local database (soft delete)
            $customer->delete();

            return redirect()->route('customers.index')
                ->with('success', __('dashboard.customer_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting customer: ' . $e->getMessage());
            return back()->with('error', __('dashboard.error_deleting_customer') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync customers from Zoho Books to local database
     */
    public function syncFromZoho(Request $request)
    {
        try {
            Log::info('Sync customers request received', [
                'is_ajax' => $request->ajax(),
                'accept_header' => $request->header('Accept'),
            ]);

            // Always run sync synchronously for immediate feedback
            set_time_limit(0); // No time limit - unlimited execution time

            // Verify ZohoBooksService is available
            if (!$this->books) {
                throw new \Exception('ZohoBooksService not initialized');
            }

            $job = new \App\Jobs\SyncCustomersFromZoho();
            $job->handle($this->books);

            $message = 'Customer synchronization completed successfully!';

            Log::info('Sync customers completed successfully');

            // Always return JSON for this endpoint
            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing customers: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Error syncing customers: ' . $e->getMessage();

            // Always return JSON for this endpoint
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }
    }
}
