<?php

namespace App\Http\Controllers;

use App\Models\Bill;
use App\Jobs\SyncBillsFromZoho;
use App\Services\ZohoBooksService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BillController extends Controller
{
    protected $books;

    public function __construct(ZohoBooksService $books)
    {
        $this->books = $books;
    }

    public function index(Request $request)
    {
        $query = Bill::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('bill_number', 'like', "%{$search}%")
                  ->orWhere('reference_number', 'like', "%{$search}%")
                  ->orWhere('vendor_name', 'like', "%{$search}%")
                  ->orWhere('notes', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $bills = $query->orderBy('bill_date', 'desc')->paginate(15);

        return view('dashboard.bill.index', compact('bills'));
    }

    public function create()
    {
        return view('dashboard.bill.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'bill_date' => 'required|date',
            'vendor_name' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Prepare data for Zoho Books
            $billData = [
                'vendor_name' => $request->vendor_name,
                'date' => $request->bill_date,
                'due_date' => $request->due_date,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'terms' => $request->terms,
            ];

            // Create bill in Zoho Books
            $zohoBill = $this->books->createBill($billData);

            // Create bill in local database
            $bill = Bill::create([
                'zoho_bill_id' => $zohoBill['bill']['bill_id'],
                'zoho_vendor_id' => $zohoBill['bill']['vendor_id'] ?? null,
                'bill_number' => $zohoBill['bill']['bill_number'],
                'bill_date' => $request->bill_date,
                'due_date' => $request->due_date,
                'reference_number' => $request->reference_number,
                'vendor_name' => $request->vendor_name,
                'total' => $request->total,
                'balance' => $request->total,
                'currency_code' => 'SAR',
                'status' => 'draft',
                'notes' => $request->notes,
                'terms' => $request->terms,
                'synced_to_zoho' => true,
                'last_synced_at' => now(),
            ]);

            DB::commit();

            return redirect()->route('bills.show', $bill)
                           ->with('success', __('dashboard.bill_created_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating bill: ' . $e->getMessage());
            return back()->withInput()
                       ->with('error', __('dashboard.error_creating_bill') . ': ' . $e->getMessage());
        }
    }

    public function show(Bill $bill)
    {
        $bill->load('items');
        return view('dashboard.bill.show', compact('bill'));
    }

    public function edit(Bill $bill)
    {
        return view('dashboard.bill.edit', compact('bill'));
    }

    public function update(Request $request, Bill $bill)
    {
        $request->validate([
            'bill_date' => 'required|date',
            'vendor_name' => 'required|string|max:255',
            'total' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // Prepare data for Zoho Books
            $billData = [
                'vendor_name' => $request->vendor_name,
                'date' => $request->bill_date,
                'due_date' => $request->due_date,
                'reference_number' => $request->reference_number,
                'notes' => $request->notes,
                'terms' => $request->terms,
            ];

            // Update bill in Zoho Books if synced
            if ($bill->synced_to_zoho && $bill->zoho_bill_id) {
                $this->books->updateBill($bill->zoho_bill_id, $billData);
            }

            // Update bill in local database
            $bill->update([
                'bill_date' => $request->bill_date,
                'due_date' => $request->due_date,
                'reference_number' => $request->reference_number,
                'vendor_name' => $request->vendor_name,
                'total' => $request->total,
                'notes' => $request->notes,
                'terms' => $request->terms,
                'last_synced_at' => $bill->synced_to_zoho ? now() : $bill->last_synced_at,
            ]);

            DB::commit();

            return redirect()->route('bills.show', $bill)
                           ->with('success', __('dashboard.bill_updated_successfully'));

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating bill: ' . $e->getMessage());
            return back()->withInput()
                       ->with('error', __('dashboard.error_updating_bill') . ': ' . $e->getMessage());
        }
    }

    public function destroy(Bill $bill)
    {
        try {
            // Delete from Zoho Books if synced
            if ($bill->synced_to_zoho && $bill->zoho_bill_id) {
                $this->books->deleteBill($bill->zoho_bill_id);
            }

            // Delete from local database
            $bill->delete();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.bill_deleted_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting bill: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_deleting_bill') . ': ' . $e->getMessage()
            ], 500);
        }
    }

    public function syncFromZoho()
    {
        try {
            set_time_limit(0);
            SyncBillsFromZoho::dispatchSync();

            return response()->json([
                'success' => true,
                'message' => __('dashboard.bills_synced_successfully')
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing bills: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => __('dashboard.error_syncing_bills') . ': ' . $e->getMessage()
            ], 500);
        }
    }
}
