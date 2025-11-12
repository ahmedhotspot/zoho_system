<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Customer;
use App\Models\Invoice;
use App\Services\ZohoBooksService;
use App\Jobs\SyncPaymentsFromZoho;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    /**
     * Display a listing of payments.
     */
    public function index(Request $request)
    {
        try {
            $query = Payment::with(['customer', 'invoice'])
                ->orderBy('payment_date', 'desc');

            // Filter by payment mode
            if ($request->filled('payment_mode')) {
                $query->where('payment_mode', $request->payment_mode);
            }

            // Filter by customer
            if ($request->filled('customer_id')) {
                $query->where('customer_id', $request->customer_id);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('payment_number', 'like', "%{$search}%")
                      ->orWhere('reference_number', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%")
                      ->orWhere('description', 'like', "%{$search}%");
                });
            }

            $perPage = $request->get('per_page', 15);
            $payments = $query->paginate($perPage);

            return view('dashboard.payment.index', compact('payments'));
        } catch (\Exception $e) {
            Log::error('Error fetching payments: ' . $e->getMessage());
            return back()->with('error', 'Error fetching payments: ' . $e->getMessage());
        }
    }

    /**
     * Show the form for creating a new payment.
     */
    public function create()
    {
        $customers = Customer::where('status', 'active')->orderBy('contact_name')->get();
        $invoices = Invoice::whereIn('status', ['sent', 'overdue', 'partially_paid'])
            ->orderBy('invoice_date', 'desc')
            ->get();

        return view('dashboard.payment.create', compact('customers', 'invoices'));
    }

    /**
     * Store a newly created payment in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'customer_id' => 'required|exists:customers,id',
                'invoice_id' => 'nullable|exists:invoices,id',
                'payment_date' => 'required|date',
                'amount' => 'required|numeric|min:0',
                'payment_mode' => 'required|string',
                'reference_number' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Get customer and invoice
            $customer = Customer::findOrFail($validated['customer_id']);
            $invoice = $validated['invoice_id'] ? Invoice::findOrFail($validated['invoice_id']) : null;

            // Prepare data for Zoho Books
            $zohoData = [
                'customer_id' => $customer->zoho_contact_id,
                'payment_mode' => $validated['payment_mode'],
                'amount' => $validated['amount'],
                'date' => $validated['payment_date'],
                'reference_number' => $validated['reference_number'] ?? null,
                'description' => $validated['description'] ?? null,
            ];

            // If invoice is selected, add it to the payment
            if ($invoice) {
                $zohoData['invoices'] = [
                    [
                        'invoice_id' => $invoice->zoho_invoice_id,
                        'amount_applied' => $validated['amount'],
                    ]
                ];
            }

            // Create payment in Zoho Books
            $zohoPayment = $this->books->createPayment($zohoData);

            // Save to local database
            $payment = Payment::create([
                'zoho_payment_id' => $zohoPayment['payment']['payment_id'],
                'zoho_customer_id' => $customer->zoho_contact_id,
                'zoho_invoice_id' => $invoice?->zoho_invoice_id,
                'payment_number' => $zohoPayment['payment']['payment_number'] ?? null,
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_mode' => $validated['payment_mode'],
                'reference_number' => $validated['reference_number'] ?? null,
                'customer_name' => $customer->contact_name,
                'customer_id' => $customer->id,
                'invoice_id' => $invoice?->id,
                'amount_applied' => $validated['amount'],
                'currency_code' => $customer->currency_code ?? 'SAR',
                'description' => $validated['description'] ?? null,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('payments.index')
                ->with('success', __('dashboard.payment_created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating payment: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_creating_payment') . ': ' . $e->getMessage());
        }
    }

    /**
     * Display the specified payment.
     */
    public function show(Payment $payment)
    {
        $payment->load(['customer', 'invoice']);
        return view('dashboard.payment.show', compact('payment'));
    }

    /**
     * Show the form for editing the specified payment.
     */
    public function edit(Payment $payment)
    {
        $customers = Customer::where('status', 'active')->orderBy('contact_name')->get();
        $invoices = Invoice::whereIn('status', ['sent', 'overdue', 'partially_paid'])
            ->orderBy('invoice_date', 'desc')
            ->get();

        return view('dashboard.payment.edit', compact('payment', 'customers', 'invoices'));
    }

    /**
     * Update the specified payment in storage.
     */
    public function update(Request $request, Payment $payment)
    {
        try {
            $validated = $request->validate([
                'payment_date' => 'required|date',
                'amount' => 'required|numeric|min:0',
                'payment_mode' => 'required|string',
                'reference_number' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ]);

            DB::beginTransaction();

            // Update payment in local database
            $payment->update([
                'payment_date' => $validated['payment_date'],
                'amount' => $validated['amount'],
                'payment_mode' => $validated['payment_mode'],
                'reference_number' => $validated['reference_number'] ?? null,
                'description' => $validated['description'] ?? null,
                'amount_applied' => $validated['amount'],
            ]);

            // Note: Zoho Books API doesn't support updating payments
            // So we only update locally

            DB::commit();

            return redirect()->route('payments.show', $payment->id)
                ->with('success', __('dashboard.payment_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating payment: ' . $e->getMessage());
            return back()->withInput()
                ->with('error', __('dashboard.error_updating_payment') . ': ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified payment from storage.
     */
    public function destroy(Payment $payment)
    {
        try {
            DB::beginTransaction();

            // Delete from Zoho Books if it exists there
            if ($payment->zoho_payment_id) {
                try {
                    $this->books->deletePayment($payment->zoho_payment_id);
                } catch (\Exception $e) {
                    Log::warning('Could not delete payment from Zoho Books: ' . $e->getMessage());
                    // Continue with local deletion even if Zoho deletion fails
                }
            }

            // Soft delete from local database
            $payment->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.payment_deleted_successfully'),
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting payment: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_deleting_payment') . ': ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Sync payments from Zoho Books.
     */
    public function syncFromZoho(Request $request)
    {
        try {
            set_time_limit(0);

            // Dispatch sync job
            SyncPaymentsFromZoho::dispatch();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.payments_synced_successfully'),
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing payments: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_payments') . ': ' . $e->getMessage(),
            ], 500);
        }
    }
}
