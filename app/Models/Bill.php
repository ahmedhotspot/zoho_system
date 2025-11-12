<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bill extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zoho_bill_id',
        'zoho_vendor_id',
        'bill_number',
        'bill_date',
        'due_date',
        'reference_number',
        'vendor_name',
        'vendor_email',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'total',
        'balance',
        'currency_code',
        'status',
        'notes',
        'terms',
        'payment_made',
        'is_item_level_tax_calc',
        'synced_to_zoho',
        'last_synced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'bill_date' => 'date',
        'due_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total' => 'decimal:2',
        'balance' => 'decimal:2',
        'payment_made' => 'decimal:2',
        'is_item_level_tax_calc' => 'boolean',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the items for the bill.
     */
    public function items(): HasMany
    {
        return $this->hasMany(BillItem::class);
    }

    /**
     * Check if bill is overdue
     */
    public function isOverdue(): bool
    {
        return $this->due_date &&
               $this->due_date->isPast() &&
               !in_array($this->status, ['paid', 'void']);
    }

    /**
     * Get date attribute (alias for bill_date)
     */
    public function getDateAttribute()
    {
        return $this->bill_date;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'open' => 'info',
            'paid' => 'success',
            'overdue' => 'danger',
            'void' => 'dark',
            'partially_paid' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Scope a query to only include bills with a specific status.
     */
    public function scopeStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope a query to only include overdue bills.
     */
    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                    ->whereNotIn('status', ['paid', 'void']);
    }

    /**
     * Scope a query to only include synced bills.
     */
    public function scopeSynced($query)
    {
        return $query->where('synced_to_zoho', true);
    }

    /**
     * Scope a query to only include unsynced bills.
     */
    public function scopeUnsynced($query)
    {
        return $query->where('synced_to_zoho', false);
    }
}
