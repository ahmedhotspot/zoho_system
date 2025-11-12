<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_expense_id',
        'zoho_account_id',
        'zoho_customer_id',
        'zoho_vendor_id',
        'zoho_project_id',
        'customer_id',
        'account_name',
        'expense_date',
        'amount',
        'reference_number',
        'description',
        'tax_id',
        'tax_name',
        'tax_percentage',
        'tax_amount',
        'is_inclusive_tax',
        'currency_id',
        'currency_code',
        'exchange_rate',
        'sub_total',
        'total',
        'is_billable',
        'is_personal',
        'customer_name',
        'status',
        'invoice_id',
        'invoice_number',
        'project_name',
        'vendor_name',
        'expense_receipt_name',
        'expense_receipt_type',
        'synced_to_zoho',
        'last_synced_at',
    ];

    protected $casts = [
        'expense_date' => 'date',
        'amount' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'is_inclusive_tax' => 'boolean',
        'exchange_rate' => 'decimal:6',
        'sub_total' => 'decimal:2',
        'total' => 'decimal:2',
        'is_billable' => 'boolean',
        'is_personal' => 'boolean',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the expense.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Scope a query to only include billable expenses.
     */
    public function scopeBillable($query)
    {
        return $query->where('is_billable', true);
    }

    /**
     * Scope a query to only include unbilled expenses.
     */
    public function scopeUnbilled($query)
    {
        return $query->where('status', 'unbilled');
    }

    /**
     * Scope a query to only include invoiced expenses.
     */
    public function scopeInvoiced($query)
    {
        return $query->where('status', 'invoiced');
    }

    /**
     * Get the status color attribute.
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'unbilled' => 'warning',
            'invoiced' => 'success',
            'reimbursed' => 'info',
            'nonbillable' => 'secondary',
            default => 'secondary',
        };
    }
}
