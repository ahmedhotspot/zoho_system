<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmLead extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_lead_id',
        'first_name',
        'last_name',
        'full_name',
        'company',
        'title',
        'designation',
        'email',
        'phone',
        'mobile',
        'fax',
        'website',
        'street',
        'city',
        'state',
        'zip_code',
        'country',
        'lead_status',
        'lead_source',
        'industry',
        'no_of_employees',
        'annual_revenue',
        'rating',
        'skype_id',
        'twitter',
        'secondary_email',
        'description',
        'owner_id',
        'owner_name',
        'is_converted',
        'converted_at',
        'converted_contact_id',
        'converted_account_id',
        'converted_deal_id',
        'created_by_id',
        'created_by_name',
        'modified_by_id',
        'modified_by_name',
        'synced_to_zoho',
        'last_synced_at',
    ];

    protected $casts = [
        'is_converted' => 'boolean',
        'synced_to_zoho' => 'boolean',
        'converted_at' => 'datetime',
        'last_synced_at' => 'datetime',
        'annual_revenue' => 'decimal:2',
        'no_of_employees' => 'integer',
    ];

    // Scopes
    public function scopeNotConverted($query)
    {
        return $query->where('is_converted', false);
    }

    public function scopeConverted($query)
    {
        return $query->where('is_converted', true);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('lead_status', $status);
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('lead_source', $source);
    }

    public function scopeSynced($query)
    {
        return $query->where('synced_to_zoho', true);
    }

    public function scopeUnsynced($query)
    {
        return $query->where('synced_to_zoho', false);
    }

    // Accessors
    public function getLeadStatusColorAttribute(): string
    {
        return match($this->lead_status) {
            'Qualified', 'Pre-Qualified' => 'success',
            'Contacted', 'Attempted to Contact' => 'primary',
            'Not Contacted' => 'warning',
            'Unqualified', 'Lost Lead', 'Junk Lead' => 'danger',
            default => 'secondary',
        };
    }

    public function getRatingColorAttribute(): string
    {
        return match($this->rating) {
            'Hot' => 'danger',
            'Warm' => 'warning',
            'Cold' => 'info',
            default => 'secondary',
        };
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->street,
            $this->city,
            $this->state,
            $this->zip_code,
            $this->country,
        ]);

        return implode(', ', $parts);
    }
}
