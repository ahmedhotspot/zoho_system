<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ZohoBooksService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Item;

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
            // Get invoices from local database
            $query = Invoice::with('items')->orderBy('invoice_date', 'desc');

            // Apply filters
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('invoice_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%");
                });
            }

            // Paginate results
            $perPage = $request->get('per_page', 15);
            $invoices = $query->paginate($perPage);

            // For AJAX requests, return JSON
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'invoices' => $invoices->items(),
                    'pagination' => [
                        'total' => $invoices->total(),
                        'per_page' => $invoices->perPage(),
                        'current_page' => $invoices->currentPage(),
                        'last_page' => $invoices->lastPage(),
                    ],
                    'total' => $invoices->total()
                ]);
            }

            // For regular requests, pass data to view
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
            $invoices = collect([]);
            return view('dashboard.invoice.index', compact('invoices'))
                ->with('error', 'Unable to fetch invoices');
        }
    }

    /**
     * Show the form for creating a new invoice
     */
    public function create()
    {
        try {
            // Get customers from local database (only customers, not vendors)
            $customers = Customer::active()
                ->customers()
                ->select('id', 'zoho_contact_id', 'contact_name', 'company_name', 'email', 'phone')
                ->orderBy('contact_name')
                ->get()
                ->map(function ($customer) {
                    return [
                        'contact_id' => $customer->zoho_contact_id,
                        'contact_name' => $customer->contact_name,
                        'company_name' => $customer->company_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                    ];
                })
                ->toArray();

            $items = Item::active()
                ->sales()
                ->select('id', 'zoho_item_id', 'name', 'description', 'rate', 'tax_percentage', 'unit')
                ->orderBy('name')
                ->get()
                ->map(function ($item) {
                    return [
                        'item_id' => $item->zoho_item_id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'rate' => $item->rate,
                        'tax_percentage' => $item->tax_percentage,
                        'unit' => $item->unit,
                    ];
                })
                ->toArray();

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

        DB::beginTransaction();

        try {
            // Prepare data for Zoho Books
            $zohoInvoiceData = [
                'customer_id' => $request->customer_id,
                'date' => $request->date ?? now()->format('Y-m-d'),
                'due_date' => $request->due_date,
                'line_items' => $request->line_items,
                'notes' => $request->notes,
                'terms' => $request->terms,
            ];

            // Create invoice in Zoho Books first
            $zohoResponse = $this->books->createInvoice($zohoInvoiceData);
            $zohoInvoice = $zohoResponse['invoice'] ?? null;

            if (!$zohoInvoice) {
                throw new \Exception('Failed to create invoice in Zoho Books');
            }

            // Get customer details from Zoho
            $customerData = $this->books->getCustomer($request->customer_id);
            $customer = $customerData['contact'] ?? null;

            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            foreach ($request->line_items as $item) {
                $itemSubtotal = $item['quantity'] * $item['rate'];
                $subtotal += $itemSubtotal;

                if (isset($item['tax_percentage'])) {
                    $taxAmount += ($itemSubtotal * $item['tax_percentage']) / 100;
                }

                if (isset($item['discount_amount'])) {
                    $discountAmount += $item['discount_amount'];
                }
            }

            $total = $subtotal + $taxAmount - $discountAmount;

            // Generate invoice number if not provided
            $invoiceNumber = $zohoInvoice['invoice_number'] ?? 'INV-' . now()->format('Ymd') . '-' . str_pad(Invoice::count() + 1, 4, '0', STR_PAD_LEFT);

            // Save invoice to local database
            $localInvoice = Invoice::create([
                'zoho_invoice_id' => $zohoInvoice['invoice_id'] ?? null,
                'zoho_customer_id' => $request->customer_id,
                'invoice_url' => $zohoInvoice['invoice_url'] ?? null,
                'invoice_number' => $invoiceNumber,
                'invoice_date' => $request->date ?? now()->format('Y-m-d'),
                'due_date' => $request->due_date,
                'customer_name' => $customer['contact_name'] ?? 'Unknown Customer',
                'customer_email' => $customer['email'] ?? null,
                'customer_address' => $customer['billing_address']['address'] ?? null,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'currency_code' => $zohoInvoice['currency_code'] ?? 'SAR',
                'status' => $zohoInvoice['status'] ?? 'draft',
                'notes' => $request->notes,
                'terms' => $request->terms,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ]);

            // Save invoice items to local database
            foreach ($request->line_items as $item) {
                // Get item details from Zoho if available
                $itemDetails = null;
                if (isset($item['item_id'])) {
                    try {
                        $itemData = $this->books->getItem($item['item_id']);
                        $itemDetails = $itemData['item'] ?? null;
                    } catch (\Exception $e) {
                        Log::warning('Could not fetch item details: ' . $e->getMessage());
                    }
                }

                $itemSubtotal = $item['quantity'] * $item['rate'];
                $itemTaxPercentage = $item['tax_percentage'] ?? 0;
                $itemTaxAmount = ($itemSubtotal * $itemTaxPercentage) / 100;
                $itemDiscountAmount = $item['discount_amount'] ?? 0;
                $itemAmount = $itemSubtotal + $itemTaxAmount - $itemDiscountAmount;

                InvoiceItem::create([
                    'invoice_id' => $localInvoice->id,
                    'zoho_item_id' => $item['item_id'] ?? null,
                    'item_name' => $itemDetails['name'] ?? $item['name'] ?? 'Unknown Item',
                    'description' => $item['description'] ?? $itemDetails['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'rate' => $item['rate'],
                    'amount' => $itemAmount,
                    'tax_percentage' => $itemTaxPercentage,
                    'tax_amount' => $itemTaxAmount,
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'discount_amount' => $itemDiscountAmount,
                ]);
            }

            DB::commit();

            Log::info('Invoice created successfully', [
                'local_id' => $localInvoice->id,
                'zoho_id' => $zohoInvoice['invoice_id'] ?? null,
                'invoice_number' => $invoiceNumber
            ]);

            return redirect()->route('invoices.show', $localInvoice->id)
                ->with('success', 'Invoice created successfully in both local database and Zoho Books');

        } catch (\Exception $e) {
            DB::rollBack();
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
            // Get invoice from local database with items
            $invoice = Invoice::with('items')->find($id);

            if (!$invoice) {
                return redirect()->route('invoices.index')
                    ->with('error', 'Invoice not found');
            }

            // Convert to array format for compatibility with existing views
            $invoiceArray = [
                'invoice_id' => $invoice->zoho_invoice_id,
                'invoice_url' => $invoice->invoice_url,
                'invoice_number' => $invoice->invoice_number,
                'date' => $invoice->invoice_date->format('Y-m-d'),
                'due_date' => $invoice->due_date ? $invoice->due_date->format('Y-m-d') : null,
                'customer_name' => $invoice->customer_name,
                'customer_email' => $invoice->customer_email,
                'status' => $invoice->status,
                'total' => $invoice->total,
                'sub_total' => $invoice->subtotal,
                'tax_total' => $invoice->tax_amount,
                'discount_total' => $invoice->discount_amount,
                'currency_code' => $invoice->currency_code,
                'notes' => $invoice->notes,
                'terms' => $invoice->terms,
                'line_items' => $invoice->items->map(function($item) {
                    return [
                        'item_id' => $item->zoho_item_id,
                        'name' => $item->item_name,
                        'description' => $item->description,
                        'quantity' => $item->quantity,
                        'rate' => $item->rate,
                        'amount' => $item->amount,
                        'item_total' => $item->amount,
                    ];
                })->toArray(),
            ];

            return view('dashboard.invoice.show', ['invoice' => $invoiceArray]);

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
            // Get invoice from local database
            $localInvoice = Invoice::with('items')->findOrFail($id);

            // Only allow editing of draft invoices
            if ($localInvoice->status !== 'draft') {
                return redirect()->route('invoices.show', $id)
                    ->with('error', 'Only draft invoices can be edited');
            }

            // Convert to array format expected by the view
            $invoice = [
                'invoice_id' => $localInvoice->zoho_invoice_id,
                'customer_id' => $localInvoice->zoho_customer_id,
                'invoice_number' => $localInvoice->invoice_number,
                'date' => $localInvoice->invoice_date,
                'due_date' => $localInvoice->due_date,
                'status' => $localInvoice->status,
                'sub_total' => $localInvoice->subtotal,
                'tax_total' => $localInvoice->tax_amount,
                'total' => $localInvoice->total,
                'notes' => $localInvoice->notes,
                'terms' => $localInvoice->terms,
                'line_items' => $localInvoice->items->map(function ($lineItem) {
                    return [
                        'item_id' => $lineItem->zoho_item_id,
                        'name' => $lineItem->item_name,
                        'description' => $lineItem->description,
                        'quantity' => $lineItem->quantity,
                        'rate' => $lineItem->rate,
                        'tax_percentage' => $lineItem->tax_percentage,
                        'item_total' => $lineItem->amount,
                    ];
                })->toArray(),
            ];

            // Get customers from local database (only customers, not vendors)
            $customers = Customer::active()
                ->customers()
                ->select('id', 'zoho_contact_id', 'contact_name', 'company_name', 'email', 'phone')
                ->orderBy('contact_name')
                ->get()
                ->map(function ($customer) {
                    return [
                        'contact_id' => $customer->zoho_contact_id,
                        'contact_name' => $customer->contact_name,
                        'company_name' => $customer->company_name,
                        'email' => $customer->email,
                        'phone' => $customer->phone,
                    ];
                })
                ->toArray();

            // Get items from local database (only sales items)
            $items = Item::active()
                ->sales()
                ->select('id', 'zoho_item_id', 'name', 'description', 'rate', 'tax_percentage', 'unit')
                ->orderBy('name')
                ->get()
                ->map(function ($item) {
                    return [
                        'item_id' => $item->zoho_item_id,
                        'name' => $item->name,
                        'description' => $item->description,
                        'rate' => $item->rate,
                        'tax_percentage' => $item->tax_percentage,
                        'unit' => $item->unit,
                    ];
                })
                ->toArray();

            return view('dashboard.invoice.create', compact('invoice', 'customers', 'items'));

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

        DB::beginTransaction();

        try {
            // Get local invoice
            $localInvoice = Invoice::find($id);

            if (!$localInvoice) {
                throw new \Exception('Invoice not found in local database');
            }

            // Prepare data for Zoho Books
            $zohoInvoiceData = [
                'customer_id' => $request->customer_id,
                'date' => $request->date,
                'due_date' => $request->due_date,
                'line_items' => $request->line_items,
                'notes' => $request->notes,
                'terms' => $request->terms,
            ];

            // Update invoice in Zoho Books if synced
            $zohoInvoice = null;
            if ($localInvoice->zoho_invoice_id) {
                $zohoResponse = $this->books->updateInvoice($localInvoice->zoho_invoice_id, $zohoInvoiceData);
                $zohoInvoice = $zohoResponse['invoice'] ?? null;
            }

            // Get customer details from Zoho
            $customerData = $this->books->getCustomer($request->customer_id);
            $customer = $customerData['contact'] ?? null;

            // Calculate totals
            $subtotal = 0;
            $taxAmount = 0;
            $discountAmount = 0;

            foreach ($request->line_items as $item) {
                $itemSubtotal = $item['quantity'] * $item['rate'];
                $subtotal += $itemSubtotal;

                if (isset($item['tax_percentage'])) {
                    $taxAmount += ($itemSubtotal * $item['tax_percentage']) / 100;
                }

                if (isset($item['discount_amount'])) {
                    $discountAmount += $item['discount_amount'];
                }
            }

            $total = $subtotal + $taxAmount - $discountAmount;

            // Update local invoice
            $localInvoice->update([
                'zoho_customer_id' => $request->customer_id,
                'invoice_url' => $zohoInvoice['invoice_url'] ?? $localInvoice->invoice_url,
                'invoice_date' => $request->date,
                'due_date' => $request->due_date,
                'customer_name' => $customer['contact_name'] ?? $localInvoice->customer_name,
                'customer_email' => $customer['email'] ?? $localInvoice->customer_email,
                'customer_address' => $customer['billing_address']['address'] ?? $localInvoice->customer_address,
                'subtotal' => $subtotal,
                'tax_amount' => $taxAmount,
                'discount_amount' => $discountAmount,
                'total' => $total,
                'notes' => $request->notes,
                'terms' => $request->terms,
                'last_synced_at' => now(),
            ]);

            // Delete old items and create new ones
            $localInvoice->items()->delete();

            // Save new invoice items
            foreach ($request->line_items as $item) {
                // Get item details from Zoho if available
                $itemDetails = null;
                if (isset($item['item_id'])) {
                    try {
                        $itemData = $this->books->getItem($item['item_id']);
                        $itemDetails = $itemData['item'] ?? null;
                    } catch (\Exception $e) {
                        Log::warning('Could not fetch item details: ' . $e->getMessage());
                    }
                }

                $itemSubtotal = $item['quantity'] * $item['rate'];
                $itemTaxPercentage = $item['tax_percentage'] ?? 0;
                $itemTaxAmount = ($itemSubtotal * $itemTaxPercentage) / 100;
                $itemDiscountAmount = $item['discount_amount'] ?? 0;
                $itemAmount = $itemSubtotal + $itemTaxAmount - $itemDiscountAmount;

                InvoiceItem::create([
                    'invoice_id' => $localInvoice->id,
                    'zoho_item_id' => $item['item_id'] ?? null,
                    'item_name' => $itemDetails['name'] ?? $item['name'] ?? 'Unknown Item',
                    'description' => $item['description'] ?? $itemDetails['description'] ?? null,
                    'quantity' => $item['quantity'],
                    'rate' => $item['rate'],
                    'amount' => $itemAmount,
                    'tax_percentage' => $itemTaxPercentage,
                    'tax_amount' => $itemTaxAmount,
                    'discount_percentage' => $item['discount_percentage'] ?? 0,
                    'discount_amount' => $itemDiscountAmount,
                ]);
            }

            DB::commit();

            Log::info('Invoice updated successfully', [
                'local_id' => $localInvoice->id,
                'zoho_id' => $localInvoice->zoho_invoice_id,
            ]);

            return redirect()->route('invoices.show', $id)
                ->with('success', 'Invoice updated successfully in both local database and Zoho Books');

        } catch (\Exception $e) {
            DB::rollBack();
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
        DB::beginTransaction();

        try {
            // Get local invoice
            $localInvoice = Invoice::find($id);

            if (!$localInvoice) {
                throw new \Exception('Invoice not found in local database');
            }

            $emailData = $request->only(['to_mail_ids', 'cc_mail_ids', 'subject', 'body']);

            // Send via Zoho Books if synced
            if ($localInvoice->zoho_invoice_id) {
                $this->books->sendInvoice($localInvoice->zoho_invoice_id, $emailData);
            }

            // Update status to sent
            $localInvoice->update([
                'status' => 'sent',
                'last_synced_at' => now(),
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice sent successfully'
                ]);
            }

            return redirect()->route('invoices.show', $id)
                ->with('success', 'Invoice sent successfully');

        } catch (\Exception $e) {
            DB::rollBack();
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
        DB::beginTransaction();

        try {
            // Get local invoice
            $localInvoice = Invoice::find($id);

            if (!$localInvoice) {
                throw new \Exception('Invoice not found in local database');
            }

            // Mark as sent in Zoho Books if synced
            if ($localInvoice->zoho_invoice_id) {
                $this->books->markInvoiceAsSent($localInvoice->zoho_invoice_id);
            }

            // Update status in local database
            $localInvoice->update([
                'status' => 'sent',
                'last_synced_at' => now(),
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice marked as sent successfully'
                ]);
            }

            return redirect()->route('invoices.show', $id)
                ->with('success', 'Invoice marked as sent successfully');

        } catch (\Exception $e) {
            DB::rollBack();
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
        DB::beginTransaction();

        try {
            // Get local invoice
            $localInvoice = Invoice::find($id);

            if (!$localInvoice) {
                throw new \Exception('Invoice not found in local database');
            }

            // Void in Zoho Books if synced
            if ($localInvoice->zoho_invoice_id) {
                $this->books->voidInvoice($localInvoice->zoho_invoice_id);
            }

            // Update status in local database
            $localInvoice->update([
                'status' => 'void',
                'last_synced_at' => now(),
            ]);

            DB::commit();

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice voided successfully'
                ]);
            }

            return redirect()->route('invoices.show', $id)
                ->with('success', 'Invoice voided successfully');

        } catch (\Exception $e) {
            DB::rollBack();
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
        DB::beginTransaction();

        try {
            // Get local invoice
            $localInvoice = Invoice::find($id);

            if (!$localInvoice) {
                throw new \Exception('Invoice not found in local database');
            }

            // Delete from Zoho Books if synced
            if ($localInvoice->zoho_invoice_id) {
                try {
                    $this->books->deleteInvoice($localInvoice->zoho_invoice_id);
                } catch (\Exception $e) {
                    Log::warning('Could not delete invoice from Zoho Books: ' . $e->getMessage());
                    // Continue with local deletion even if Zoho deletion fails
                }
            }

            // Delete from local database (soft delete)
            $localInvoice->delete();

            DB::commit();

            Log::info('Invoice deleted successfully', [
                'local_id' => $id,
                'zoho_id' => $localInvoice->zoho_invoice_id,
            ]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Invoice deleted successfully'
                ]);
            }

            return redirect()->route('invoices.index')
                ->with('success', 'Invoice deleted successfully');

        } catch (\Exception $e) {
            DB::rollBack();
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

    /**
     * Sync invoices from Zoho Books to local database
     */
    public function syncFromZoho(Request $request)
    {
        try {
            // Check if request expects JSON (more reliable than ajax())
            $wantsJson = $request->wantsJson() || $request->expectsJson() || $request->ajax();

            Log::info('Sync request received', [
                'user_id' => auth()->id(),
                'wants_json' => $wantsJson,
                'is_ajax' => $request->ajax(),
                'accept_header' => $request->header('Accept'),
                'content_type' => $request->header('Content-Type')
            ]);

            // Always run sync synchronously for immediate feedback
            // This ensures the user gets accurate results
            set_time_limit(0); // No time limit - unlimited execution time

            // Verify ZohoBooksService is available
            if (!$this->books) {
                throw new \Exception('ZohoBooksService not initialized');
            }

            $job = new \App\Jobs\SyncInvoicesFromZoho();
            $job->handle($this->books);

            $message = 'Invoice synchronization completed successfully!';

            Log::info('Sync completed successfully', [
                'will_return_json' => $wantsJson
            ]);

            // Always return JSON for this endpoint
            return response()->json([
                'success' => true,
                'message' => $message
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing invoices: ' . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);

            $errorMessage = 'Error syncing invoices: ' . $e->getMessage();

            // Always return JSON for this endpoint
            return response()->json([
                'success' => false,
                'message' => $errorMessage
            ], 500);
        }
    }
}
