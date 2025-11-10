<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ZohoBooksService;
use Illuminate\Support\Facades\Log;

class InvoiceController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    /**
     * Display a listing of invoices
     */
    public function index(Request $request)
    {
        try {
            // Get invoices from Zoho Books API
            $params = $request->only(['status', 'customer_id', 'page', 'per_page', 'search']);
            $invoicesData = $this->books->getInvoices($params);

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'invoices' => $invoicesData['invoices'] ?? [],
                    'pagination' => $invoicesData['page_context'] ?? null,
                    'total' => $invoicesData['page_context']['total'] ?? 0
                ]);
            }

            // For regular requests, pass data to view
            $invoices = $invoicesData['invoices'] ?? [];
            $invoice= collect($invoices);
            return view('dashboard.invoice.index', compact('invoices'));

        } catch (\Exception $e) {
            Log::error('Error fetching invoices: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error fetching invoices: ' . $e->getMessage()
                ], 500);
            }

            // Return view with empty data on error
            $invoices = [];
            return view('dashboard.invoice.index', compact('invoices'))
                ->with('error', 'Unable to fetch invoices from Zoho Books');
        }
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create()
    {

        try {
            // Get all contacts first
            $customersData = $this->books->getCustomers();
            $allContacts = $customersData['contacts'] ?? [];

            // Filter to get only customers (not vendors)
            $customers = collect($allContacts)->filter(function ($contact) {
                // Check if contact_type exists and is 'customer', or if contact_type is not set (default to customer)
                return !isset($contact['contact_type']) ||
                       $contact['contact_type'] === 'customer' ||
                       $contact['contact_type'] === '';
            })->values()->all();

            // Get items for line items - filter sales items only
            $itemsData = $this->books->getItems();
            $allItems = $itemsData['items'] ?? [];

            // Filter to get only sales items (exclude purchase-only items)
            $items = collect($allItems)->filter(function ($item) {
                // Include items that have sales information
                return isset($item['rate']) ||
                       isset($item['sales_rate']) ||
                       (isset($item['item_type']) && $item['item_type'] !== 'purchase') ||
                       !isset($item['is_purchase_item']) ||
                       $item['is_purchase_item'] === false;
            })->values()->all();

            return view('dashboard.invoice.create', compact('customers', 'items'));

        } catch (\Exception $e) {
            Log::error('Error loading invoice create form: ' . $e->getMessage());
            return redirect()->route('invoices.index')
                ->with('error', 'Unable to load invoice creation form');
        }
    }

    /**
     * Store a newly created invoice
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'required',
            'line_items' => 'required|array',
            'line_items.*.item_id' => 'required',
            'line_items.*.quantity' => 'required|numeric|min:1',
            'line_items.*.rate' => 'required|numeric|min:0',
        ]);

        try {
            $invoiceData = [
                'customer_id' => $request->customer_id,
                'date' => $request->date ?? now()->format('Y-m-d'),
                'due_date' => $request->due_date,
                'line_items' => $request->line_items,
                'notes' => $request->notes,
                'terms' => $request->terms,
            ];

            $invoice = $this->books->createInvoice($invoiceData);

            return redirect()->route('invoices.show', $invoice['invoice']['invoice_id'])
                ->with('success', 'Invoice created successfully');

        } catch (\Exception $e) {
            Log::error('Error creating invoice: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified invoice
     */
    public function show($id)
    {
        try {
            $invoiceData = $this->books->getInvoice($id);
            $invoice = $invoiceData['invoice'] ?? null;

            if (!$invoice) {
                return redirect()->route('invoices.index')
                    ->with('error', 'Invoice not found');
            }

            return view('dashboard.invoice.show', compact('invoice'));

        } catch (\Exception $e) {
            Log::error('Error fetching invoice: ' . $e->getMessage());
            return redirect()->route('invoices.index')
                ->with('error', 'Unable to fetch invoice details');
        }
    }

    /**
     * Show the form for editing the specified invoice
     */
    public function edit($id)
    {
        try {
            $invoiceData = $this->books->getInvoice($id);
            $invoice = $invoiceData['invoice'] ?? null;

            if (!$invoice) {
                return redirect()->route('invoices.index')
                    ->with('error', 'Invoice not found');
            }

            // Only allow editing of draft invoices
            if ($invoice['status'] !== 'draft') {
                return redirect()->route('invoices.show', $id)
                    ->with('error', 'Only draft invoices can be edited');
            }

            // Get all contacts first
            $customersData = $this->books->getCustomers();
            $allContacts = $customersData['contacts'] ?? [];

            // Filter to get only customers (not vendors)
            $customers = collect($allContacts)->filter(function ($contact) {
                // Check if contact_type exists and is 'customer', or if contact_type is not set (default to customer)
                return !isset($contact['contact_type']) ||
                       $contact['contact_type'] === 'customer' ||
                       $contact['contact_type'] === '';
            })->values()->all();

            // Get items for line items - filter sales items only
            $itemsData = $this->books->getItems();
            $allItems = $itemsData['items'] ?? [];

            // Filter to get only sales items (exclude purchase-only items)
            $items = collect($allItems)->filter(function ($item) {
                // Include items that have sales information
                return isset($item['rate']) ||
                       isset($item['sales_rate']) ||
                       (isset($item['item_type']) && $item['item_type'] !== 'purchase') ||
                       !isset($item['is_purchase_item']) ||
                       $item['is_purchase_item'] === false;
            })->values()->all();

            return view('dashboard.invoice.edit', compact('invoice', 'customers', 'items'));

        } catch (\Exception $e) {
            Log::error('Error loading invoice edit form: ' . $e->getMessage());
            return redirect()->route('invoices.index')
                ->with('error', 'Unable to load invoice edit form');
        }
    }

    /**
     * Update the specified invoice
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'customer_id' => 'required',
            'line_items' => 'required|array',
            'line_items.*.item_id' => 'required',
            'line_items.*.quantity' => 'required|numeric|min:1',
            'line_items.*.rate' => 'required|numeric|min:0',
        ]);

        try {
            $invoiceData = [
                'customer_id' => $request->customer_id,
                'date' => $request->date,
                'due_date' => $request->due_date,
                'line_items' => $request->line_items,
                'notes' => $request->notes,
                'terms' => $request->terms,
            ];

            $invoice = $this->books->updateInvoice($id, $invoiceData);

            return redirect()->route('invoices.show', $id)
                ->with('success', 'Invoice updated successfully');

        } catch (\Exception $e) {
            Log::error('Error updating invoice: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', 'Error updating invoice: ' . $e->getMessage());
        }
    }

    /**
     * Send invoice via email
     */
    public function send(Request $request, $id)
    {
        try {
            $emailData = $request->only(['to_mail_ids', 'cc_mail_ids', 'subject', 'body']);
            $result = $this->books->sendInvoice($id, $emailData);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice sent successfully'
                ]);
            }

            return redirect()->route('invoices.show', $id)
                ->with('success', 'Invoice sent successfully');

        } catch (\Exception $e) {
            Log::error('Error sending invoice: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error sending invoice: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error sending invoice: ' . $e->getMessage());
        }
    }

    /**
     * Mark invoice as sent
     */
    public function markAsSent(Request $request, $id)
    {
        try {
            $result = $this->books->markInvoiceAsSent($id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice marked as sent successfully'
                ]);
            }

            return redirect()->route('invoices.show', $id)
                ->with('success', 'Invoice marked as sent successfully');

        } catch (\Exception $e) {
            Log::error('Error marking invoice as sent: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error marking invoice as sent: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error marking invoice as sent: ' . $e->getMessage());
        }
    }

    /**
     * Void the specified invoice
     */
    public function void(Request $request, $id)
    {
        try {
            $result = $this->books->voidInvoice($id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice voided successfully'
                ]);
            }

            return redirect()->route('invoices.show', $id)
                ->with('success', 'Invoice voided successfully');

        } catch (\Exception $e) {
            Log::error('Error voiding invoice: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error voiding invoice: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error voiding invoice: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified invoice
     */
    public function destroy(Request $request, $id)
    {
        try {
            $result = $this->books->deleteInvoice($id);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice deleted successfully'
                ]);
            }

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice deleted successfully');

        } catch (\Exception $e) {
            Log::error('Error deleting invoice: ' . $e->getMessage());

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error deleting invoice: ' . $e->getMessage()
                ], 500);
            }

            return back()->with('error', 'Error deleting invoice: ' . $e->getMessage());
        }
    }
}
