<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Estimate extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zoho_estimate_id',
        'zoho_customer_id',
        'estimate_number',
        'estimate_date',
        'expiry_date',
        'reference_number',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_address',
        'subtotal',
        'tax_amount',
        'discount_amount',
        'adjustment',
        'total',
        'currency_code',
        'status',
        'notes',
        'terms',
        'salesperson_name',
        'synced_to_zoho',
        'last_synced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'estimate_date' => 'date',
        'expiry_date' => 'date',
        'subtotal' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'adjustment' => 'decimal:2',
        'total' => 'decimal:2',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the items for the estimate.
     */
    public function items(): HasMany
    {
        return $this->hasMany(EstimateItem::class);
    }

    /**
     * Get the customer that owns the estimate.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Check if estimate is expired
     */
    public function isExpired(): bool
    {
        return $this->expiry_date &&
               $this->expiry_date->isPast() &&
               !in_array($this->status, ['accepted', 'declined', 'invoiced']);
    }

    /**
     * Get status color for badge
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'secondary',
            'sent' => 'info',
            'accepted' => 'success',
            'declined' => 'danger',
            'invoiced' => 'primary',
            'expired' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Scope a query to only include active estimates.
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', ['draft', 'sent']);
    }

    /**
     * Scope a query to only include accepted estimates.
     */
    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    /**
     * Scope a query to only include expired estimates.
     */
    public function scopeExpired($query)
    {
        return $query->where('status', 'expired');
    }
}
