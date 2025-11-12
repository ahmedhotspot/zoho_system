<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Invoice extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zoho_invoice_id',
        'zoho_customer_id',
        'invoice_number',
        'invoice_date',
        'due_date',
        'customer_name',
        'customer_email',
        'customer_address',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total',
        'currency_code',
        'status',
        'notes',
        'terms',
        'synced_to_zoho',
        'last_synced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the items for the invoice.
     */
    public function items(): HasMany
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the payments for the invoice.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Check if invoice is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date &&
               $this->due_date->isPast() &&
               !in_array($this->status, ['paid', 'void']);
    }

    /**
     * Get date attribute (alias for invoice_date)
     */
    public function getDateAttribute()
    {
        return $this->invoice_date;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'sent' => 'info',
            'paid' => 'success',
            'overdue' => 'danger',
            'void' => 'dark',
            'partially_paid' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Scope a query to only include invoices with a specific status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include overdue invoices.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['paid', 'void']);
    }

    /**
     * Scope a query to only include synced invoices.
     */
    public function scopeSynced($query)
    {
        return $query->where('synced_to_zoho', true);
    }

    /**
     * Scope a query to only include unsynced invoices.
     */
    public function scopeUnsynced($query)
    {
        return $query->where('synced_to_zoho', false);
    }
}
