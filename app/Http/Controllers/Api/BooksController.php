<?php

namespace App\Http\Controllers\Api;

use App\Services\ZohoBooksService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BooksController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    // ==========================================
    // ORGANIZATIONS
    // ==========================================

    public function getOrganizations()
    {
        try {
            $organizations = $this->books->getOrganizations();
            return response()->json($organizations);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // INVOICES
    // ==========================================

    public function getInvoices(Request $request)
    {
        try {
            $params = $request->only(['status', 'customer_id', 'page', 'per_page']);
            $invoices = $this->books->getInvoices($params);
            return response()->json($invoices);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getInvoice($id)
    {
        try {
            $invoice = $this->books->getInvoice($id);
            return response()->json($invoice);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createInvoice(Request $request)
    {
        try {
            $invoice = $this->books->createInvoice([
                'customer_id' => $request->customer_id,
                'invoice_number' => $request->invoice_number,
                'date' => $request->date ?? now()->format('Y-m-d'),
                'due_date' => $request->due_date,
                'line_items' => $request->line_items,

            ]);

            return response()->json($invoice, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateInvoice(Request $request, $id)
    {
        try {
            $invoice = $this->books->updateInvoice($id, [
                'customer_id' => $request->customer_id,
                'invoice_number' => $request->invoice_number,
                'date' => $request->date,
                'due_date' => $request->due_date,
                'line_items' => $request->line_items,
            ]);

            return response()->json($invoice);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteInvoice($id)
    {
        try {
            $result = $this->books->deleteInvoice($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

/**
 * Send invoice via email
 */
public function sendInvoice(Request $request, $id)
{
    try {
        $emailData = [];

        // Optional: Custom email settings
        if ($request->has('to_mail_ids')) {
            $emailData['to_mail_ids'] = $request->to_mail_ids;
        }

        if ($request->has('cc_mail_ids')) {
            $emailData['cc_mail_ids'] = $request->cc_mail_ids;
        }

        if ($request->has('subject')) {
            $emailData['subject'] = $request->subject;
        }

        if ($request->has('body')) {
            $emailData['body'] = $request->body;
        }

        $result = $this->books->sendInvoice($id, $emailData);

        return response()->json([
            'success' => true,
            'message' => 'Invoice sent successfully',
            'data' => $result
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage()
        ], 500);
    }
}
    public function markInvoiceAsSent($id)
    {
        try {
            $result = $this->books->markInvoiceAsSent($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function voidInvoice($id)
    {
        try {
            $result = $this->books->voidInvoice($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // CUSTOMERS
    // ==========================================

    public function getCustomers(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'search_text']);
            $customers = $this->books->getCustomers($params);
            return response()->json($customers);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getCustomer($id)
    {
        try {
            $customer = $this->books->getCustomer($id);
            return response()->json($customer);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createCustomer(Request $request)
    {
        try {
            $customer = $this->books->createCustomer([
                'contact_name' => $request->contact_name,
                'company_name' => $request->company_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
                'billing_address' => $request->billing_address,
                'shipping_address' => $request->shipping_address,
            ]);

            return response()->json($customer, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateCustomer(Request $request, $id)
    {
        try {
            $customer = $this->books->updateCustomer($id, [
                'contact_name' => $request->contact_name,
                'company_name' => $request->company_name,
                'email' => $request->email,
                'phone' => $request->phone,
                'website' => $request->website,
            ]);

            return response()->json($customer);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteCustomer($id)
    {
        try {
            $result = $this->books->deleteCustomer($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // ITEMS / PRODUCTS
    // ==========================================

    public function getItems(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'search_text']);
            $items = $this->books->getItems($params);
            return response()->json($items);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getItem($id)
    {
        try {
            $item = $this->books->getItem($id);
            return response()->json($item);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createItem(Request $request)
    {
        try {
            $item = $this->books->createItem([
                'name' => $request->name,
                'rate' => $request->rate,
                'description' => $request->description,
                'sku' => $request->sku,
                'unit' => $request->unit,
            ]);

            return response()->json($item, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateItem(Request $request, $id)
    {
        try {
            $item = $this->books->updateItem($id, [
                'name' => $request->name,
                'rate' => $request->rate,
                'description' => $request->description,
                'sku' => $request->sku,
                'unit' => $request->unit,
            ]);

            return response()->json($item);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function deleteItem($id)
    {
        try {
            $result = $this->books->deleteItem($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
 * Get all accounts
 */
public function getAccounts(Request $request)
{
    try {
        $params = $request->only(['page', 'per_page', 'filter_by']);
        $accounts = $this->books->getAccounts($params);
        return response()->json($accounts);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}

/**
 * Get expense accounts
 */
public function getExpenseAccounts()
{
    try {
        $accounts = $this->books->getExpenseAccounts();
        return response()->json($accounts);
    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


    // ==========================================
    // ESTIMATES
    // ==========================================

    public function getEstimates(Request $request)
    {
        try {
            $params = $request->only(['status', 'customer_id', 'page', 'per_page']);
            $estimates = $this->books->getEstimates($params);
            return response()->json($estimates);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createEstimate(Request $request)
    {
        try {
            $estimate = $this->books->createEstimate([
                'customer_id' => $request->customer_id,
                'estimate_number' => $request->estimate_number,
                'date' => $request->date ?? now()->format('Y-m-d'),
                'line_items' => $request->line_items,
            ]);

            return response()->json($estimate, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateEstimate(Request $request, $id)
    {
        try {
            $estimate = $this->books->updateEstimate($id, [
                'customer_id' => $request->customer_id,
                'estimate_number' => $request->estimate_number,
                'date' => $request->date,
                'line_items' => $request->line_items,
            ]);

            return response()->json($estimate);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // BILLS
    // ==========================================

    public function getBills(Request $request)
    {
        try {
            $params = $request->only(['status', 'vendor_id', 'page', 'per_page']);
            $bills = $this->books->getBills($params);
            return response()->json($bills);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createBill(Request $request)
    {
        try {
            $bill = $this->books->createBill([
                'vendor_id' => $request->vendor_id,
                'bill_number' => $request->bill_number,
                'date' => $request->date ?? now()->format('Y-m-d'),
                'due_date' => $request->due_date,
                'line_items' => $request->line_items,
            ]);

            return response()->json($bill, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // EXPENSES
    // ==========================================

    public function getExpenses(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'date']);
            $expenses = $this->books->getExpenses($params);
            return response()->json($expenses);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createExpense(Request $request)
    {
        try {
            $expense = $this->books->createExpense([
                'account_id' => $request->account_id,
                'date' => $request->date ?? now()->format('Y-m-d'),
                'amount' => $request->amount,
                'description' => $request->description,
                'vendor_id' => $request->vendor_id,
                'currency_id' => $request->currency_id,
            ]);

            return response()->json($expense, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // ==========================================
    // PAYMENTS
    // ==========================================

    public function getPayments(Request $request)
    {
        try {
            $params = $request->only(['page', 'per_page', 'customer_id']);
            $payments = $this->books->getPayments($params);
            return response()->json($payments);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function createPayment(Request $request)
    {
        try {
            $payment = $this->books->createPayment([
                'customer_id' => $request->customer_id,
                'payment_mode' => $request->payment_mode,
                'amount' => $request->amount,
                'date' => $request->date ?? now()->format('Y-m-d'),
                'reference_number' => $request->reference_number,
                'description' => $request->description,
                'invoices' => $request->invoices,
                // Example:
                // 'invoices' => [
                //     [
                //         'invoice_id' => '123',
                //         'amount_applied' => 100
                //     ]
                // ]
            ]);

            return response()->json($payment, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
