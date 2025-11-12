<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zoho_contact_id',
        'zoho_currency_id',
        'contact_name',
        'company_name',
        'customer_name',
        'vendor_name',
        'contact_type',
        'customer_sub_type',
        'status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'mobile',
        'website',
        'twitter',
        'facebook',
        'currency_code',
        'payment_terms',
        'payment_terms_label',
        'outstanding_receivable_amount',
        'outstanding_payable_amount',
        'unused_credits_receivable_amount',
        'unused_credits_payable_amount',
        'portal_status',
        'is_linked_with_zohocrm',
        'source',
        'language_code',
        'synced_to_zoho',
        'last_synced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'outstanding_receivable_amount' => 'decimal:2',
        'outstanding_payable_amount' => 'decimal:2',
        'unused_credits_receivable_amount' => 'decimal:2',
        'unused_credits_payable_amount' => 'decimal:2',
        'payment_terms' => 'integer',
        'is_linked_with_zohocrm' => 'boolean',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the invoices for the customer.
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'customer_name', 'contact_name');
    }

    /**
     * Get the payments for the customer.
     */
    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Get the estimates for the customer.
     */
    public function estimates(): HasMany
    {
        return $this->hasMany(Estimate::class);
    }

    /**
     * Get the expenses for the customer.
     */
    public function expenses(): HasMany
    {
        return $this->hasMany(Expense::class);
    }

    /**
     * Check if customer is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Get full name
     */
    public function getFullNameAttribute(): string
    {
        if ($this->first_name || $this->last_name) {
            return trim($this->first_name . ' ' . $this->last_name);
        }
        return $this->contact_name;
    }

    /**
     * Get display name (company or full name)
     */
    public function getDisplayNameAttribute(): string
    {
        return $this->company_name ?: $this->full_name;
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'active' => 'success',
            'inactive' => 'secondary',
            'crm' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Get contact type badge color
     */
    public function getContactTypeColorAttribute(): string
    {
        return match($this->contact_type) {
            'customer' => 'primary',
            'vendor' => 'warning',
            'both' => 'info',
            default => 'secondary',
        };
    }

    /**
     * Scope to get only customers
     */
    public function scopeCustomers($query)
    {
        return $query->whereIn('contact_type', ['customer', 'both']);
    }

    /**
     * Scope to get only vendors
     */
    public function scopeVendors($query)
    {
        return $query->whereIn('contact_type', ['vendor', 'both']);
    }

    /**
     * Scope to get only active contacts
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope to get synced contacts
     */
    public function scopeSynced($query)
    {
        return $query->where('synced_to_zoho', true);
    }
}
