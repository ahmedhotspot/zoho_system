<?php

namespace App\Http\Controllers;

use App\Jobs\SyncContactsFromZohoCRM;
use App\Models\CrmContact;
use App\Services\ZohoCRMService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CrmContactController extends Controller
{
    protected $crm;

    public function __construct(ZohoCRMService $crm)
    {
        $this->crm = $crm;
    }

    /**
     * Display a listing of contacts.
     */
    public function index(Request $request)
    {
        $query = CrmContact::query();

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%")
                    ->orWhere('full_name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('account_name', 'like', "%{$search}%");
            });
        }

        // Filter by lead source
        if ($request->filled('lead_source')) {
            $query->byLeadSource($request->lead_source);
        }

        // Filter by account
        if ($request->filled('account_id')) {
            $query->byAccount($request->account_id);
        }

        // Filter by email opt out
        if ($request->filled('email_opt_out')) {
            if ($request->email_opt_out === 'yes') {
                $query->emailOptOut();
            } elseif ($request->email_opt_out === 'no') {
                $query->where('email_opt_out', false);
            }
        }

        $contacts = $query->latest()->paginate(20);

        return view('dashboard.crm.contact.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new contact.
     */
    public function create()
    {
        return view('dashboard.crm.contact.create');
    }

    /**
     * Store a newly created contact.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'lead_source' => 'nullable|string|max:255',
            'mailing_street' => 'nullable|string',
            'mailing_city' => 'nullable|string|max:255',
            'mailing_state' => 'nullable|string|max:255',
            'mailing_zip' => 'nullable|string|max:20',
            'mailing_country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Create in Zoho CRM first
            $zohoData = [
                'First_Name' => $validated['first_name'],
                'Last_Name' => $validated['last_name'],
                'Email' => $validated['email'] ?? null,
                'Phone' => $validated['phone'] ?? null,
                'Mobile' => $validated['mobile'] ?? null,
                'Title' => $validated['title'] ?? null,
                'Department' => $validated['department'] ?? null,
                'Account_Name' => $validated['account_name'] ?? null,
                'Lead_Source' => $validated['lead_source'] ?? null,
                'Mailing_Street' => $validated['mailing_street'] ?? null,
                'Mailing_City' => $validated['mailing_city'] ?? null,
                'Mailing_State' => $validated['mailing_state'] ?? null,
                'Mailing_Zip' => $validated['mailing_zip'] ?? null,
                'Mailing_Country' => $validated['mailing_country'] ?? null,
                'Description' => $validated['description'] ?? null,
            ];

            $zohoResponse = $this->crm->createContact($zohoData);

            if (isset($zohoResponse['data'][0]['details']['id'])) {
                $zohoContactId = $zohoResponse['data'][0]['details']['id'];

                // Create in local database
                $validated['zoho_contact_id'] = $zohoContactId;
                $validated['full_name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
                $validated['synced_to_zoho'] = true;
                $validated['last_synced_at'] = now();

                CrmContact::create($validated);

                DB::commit();

                return redirect()->route('crm.contacts.index')
                    ->with('success', __('dashboard.contact_created_successfully'));
            } else {
                throw new \Exception('Failed to create contact in Zoho CRM');
            }

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating contact: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', __('dashboard.error_creating_contact') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified contact.
     */
    public function show(CrmContact $contact)
    {
        return view('dashboard.crm.contact.show', compact('contact'));
    }

    /**
     * Show the form for editing the specified contact.
     */
    public function edit(CrmContact $contact)
    {
        return view('dashboard.crm.contact.edit', compact('contact'));
    }

    /**
     * Update the specified contact.
     */
    public function update(Request $request, CrmContact $contact)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:50',
            'mobile' => 'nullable|string|max:50',
            'title' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'account_name' => 'nullable|string|max:255',
            'lead_source' => 'nullable|string|max:255',
            'mailing_street' => 'nullable|string',
            'mailing_city' => 'nullable|string|max:255',
            'mailing_state' => 'nullable|string|max:255',
            'mailing_zip' => 'nullable|string|max:20',
            'mailing_country' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        DB::beginTransaction();

        try {
            // Update in Zoho CRM if synced
            if ($contact->zoho_contact_id) {
                $zohoData = [
                    'First_Name' => $validated['first_name'],
                    'Last_Name' => $validated['last_name'],
                    'Email' => $validated['email'] ?? null,
                    'Phone' => $validated['phone'] ?? null,
                    'Mobile' => $validated['mobile'] ?? null,
                    'Title' => $validated['title'] ?? null,
                    'Department' => $validated['department'] ?? null,
                    'Account_Name' => $validated['account_name'] ?? null,
                    'Lead_Source' => $validated['lead_source'] ?? null,
                    'Mailing_Street' => $validated['mailing_street'] ?? null,
                    'Mailing_City' => $validated['mailing_city'] ?? null,
                    'Mailing_State' => $validated['mailing_state'] ?? null,
                    'Mailing_Zip' => $validated['mailing_zip'] ?? null,
                    'Mailing_Country' => $validated['mailing_country'] ?? null,
                    'Description' => $validated['description'] ?? null,
                ];

                $this->crm->updateContact($contact->zoho_contact_id, $zohoData);
                $validated['last_synced_at'] = now();
            }

            // Update in local database
            $validated['full_name'] = trim($validated['first_name'] . ' ' . $validated['last_name']);
            $contact->update($validated);

            DB::commit();

            return redirect()->route('crm.contacts.index')
                ->with('success', __('dashboard.contact_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating contact: ' . $e->getMessage());

            return back()->withInput()
                ->with('error', __('dashboard.error_updating_contact') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified contact.
     */
    public function destroy(CrmContact $contact)
    {
        try {
            // Delete from Zoho CRM if synced
            if ($contact->zoho_contact_id) {
                $this->crm->deleteContact($contact->zoho_contact_id);
            }

            $contact->delete();

            return redirect()->route('crm.contacts.index')
                ->with('success', __('dashboard.contact_deleted_successfully'));

        } catch (\Exception $e) {
            Log::error('Error deleting contact: ' . $e->getMessage());

            return back()->with('error', __('dashboard.error_deleting_contact') . ': ' . $e->getMessage());
        }
    }

    /**
     * Sync contacts from Zoho CRM.
     */
    public function syncFromZoho()
    {
        try {
            set_time_limit(0);

            // Run sync synchronously instead of dispatching to queue
            $job = new SyncContactsFromZohoCRM();
            $job->handle(app(\App\Services\ZohoCRMService::class));

            return response()->json([
                'success' => true,
                'message' => __('dashboard.contacts_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing contacts: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_contacts') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
