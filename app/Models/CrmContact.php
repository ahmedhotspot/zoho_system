<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmContact extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_contact_id',
        'first_name',
        'last_name',
        'full_name',
        'salutation',
        'title',
        'department',
        'email',
        'secondary_email',
        'phone',
        'mobile',
        'home_phone',
        'other_phone',
        'fax',
        'assistant',
        'assistant_phone',
        'mailing_street',
        'mailing_city',
        'mailing_state',
        'mailing_zip',
        'mailing_country',
        'other_street',
        'other_city',
        'other_state',
        'other_zip',
        'other_country',
        'account_id',
        'account_name',
        'vendor_name',
        'lead_source',
        'date_of_birth',
        'owner_id',
        'owner_name',
        'twitter',
        'skype_id',
        'description',
        'email_opt_out',
        'reporting_to',
        'synced_to_zoho',
        'last_synced_at',
    ];

    protected $casts = [
        'email_opt_out' => 'boolean',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
        'date_of_birth' => 'date',
    ];

    /**
     * Scopes
     */
    public function scopeByAccount($query, $accountId)
    {
        return $query->where('account_id', $accountId);
    }

    public function scopeByOwner($query, $ownerId)
    {
        return $query->where('owner_id', $ownerId);
    }

    public function scopeByLeadSource($query, $source)
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

    public function scopeEmailOptOut($query)
    {
        return $query->where('email_opt_out', true);
    }

    /**
     * Accessors
     */
    public function getFullAddressAttribute()
    {
        $parts = array_filter([
            $this->mailing_street,
            $this->mailing_city,
            $this->mailing_state,
            $this->mailing_zip,
            $this->mailing_country,
        ]);

        return implode(', ', $parts);
    }

    public function getOtherAddressAttribute()
    {
        $parts = array_filter([
            $this->other_street,
            $this->other_city,
            $this->other_state,
            $this->other_zip,
            $this->other_country,
        ]);

        return implode(', ', $parts);
    }

    /**
     * Helper Methods
     */
    public function getDisplayName()
    {
        if ($this->full_name) {
            return $this->full_name;
        }

        $name = trim(($this->salutation ? $this->salutation . ' ' : '') . $this->first_name . ' ' . $this->last_name);
        return $name ?: 'N/A';
    }
}
