<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmAccount extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_account_id',
        'account_name',
        'account_number',
        'account_type',
        'industry',
        'annual_revenue',
        'employees',
        'ownership',
        'rating',
        'sic_code',
        'ticker_symbol',
        'phone',
        'fax',
        'website',
        'billing_street',
        'billing_city',
        'billing_state',
        'billing_code',
        'billing_country',
        'shipping_street',
        'shipping_city',
        'shipping_state',
        'shipping_code',
        'shipping_country',
        'parent_account_id',
        'parent_account_name',
        'owner_id',
        'owner_name',
        'description',
        'zoho_created_time',
        'zoho_modified_time',
        'last_synced_at',
    ];

    protected $casts = [
        'employees' => 'integer',
        'zoho_created_time' => 'datetime',
        'zoho_modified_time' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Scopes
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('account_name', 'like', "%{$search}%")
              ->orWhere('account_number', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%")
              ->orWhere('website', 'like', "%{$search}%")
              ->orWhere('industry', 'like', "%{$search}%");
        });
    }

    public function scopeByType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    public function scopeByIndustry($query, $industry)
    {
        return $query->where('industry', $industry);
    }

    public function scopeByRating($query, $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Accessors
     */
    public function getFullBillingAddressAttribute()
    {
        $parts = array_filter([
            $this->billing_street,
            $this->billing_city,
            $this->billing_state,
            $this->billing_code,
            $this->billing_country,
        ]);

        return implode(', ', $parts);
    }

    public function getFullShippingAddressAttribute()
    {
        $parts = array_filter([
            $this->shipping_street,
            $this->shipping_city,
            $this->shipping_state,
            $this->shipping_code,
            $this->shipping_country,
        ]);

        return implode(', ', $parts);
    }

    public function getTypeBadgeClassAttribute()
    {
        return match($this->account_type) {
            'Customer' => 'badge-light-success',
            'Prospect' => 'badge-light-primary',
            'Partner' => 'badge-light-info',
            'Competitor' => 'badge-light-danger',
            'Vendor' => 'badge-light-warning',
            'Supplier' => 'badge-light-warning',
            default => 'badge-light-secondary',
        };
    }

    public function getRatingBadgeClassAttribute()
    {
        return match($this->rating) {
            'Hot' => 'badge-light-danger',
            'Warm' => 'badge-light-warning',
            'Cold' => 'badge-light-info',
            default => 'badge-light-secondary',
        };
    }
}
