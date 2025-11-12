<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmDeal extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_deal_id',
        'deal_name',
        'account_id',
        'account_name',
        'contact_id',
        'contact_name',
        'stage',
        'amount',
        'closing_date',
        'type',
        'lead_source',
        'next_step',
        'probability',
        'expected_revenue',
        'campaign_source',
        'owner_id',
        'owner_name',
        'description',
        'deal_category_status',
        'currency',
        'exchange_rate',
        'synced_to_zoho',
        'last_synced_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'probability' => 'decimal:2',
        'expected_revenue' => 'decimal:2',
        'exchange_rate' => 'decimal:4',
        'closing_date' => 'date',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Scopes
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('deal_name', 'like', "%{$search}%")
              ->orWhere('account_name', 'like', "%{$search}%")
              ->orWhere('contact_name', 'like', "%{$search}%")
              ->orWhere('stage', 'like', "%{$search}%")
              ->orWhere('amount', 'like', "%{$search}%");
        });
    }

    public function scopeByStage($query, $stage)
    {
        if ($stage) {
            return $query->where('stage', $stage);
        }
        return $query;
    }

    public function scopeByType($query, $type)
    {
        if ($type) {
            return $query->where('type', $type);
        }
        return $query;
    }

    public function scopeClosingThisMonth($query)
    {
        return $query->whereMonth('closing_date', now()->month)
                     ->whereYear('closing_date', now()->year);
    }

    public function scopeClosingSoon($query, $days = 30)
    {
        return $query->whereBetween('closing_date', [now(), now()->addDays($days)]);
    }

    /**
     * Accessors
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getFormattedExpectedRevenueAttribute()
    {
        return number_format($this->expected_revenue, 2) . ' ' . $this->currency;
    }

    public function getIsClosingSoonAttribute()
    {
        if (!$this->closing_date) {
            return false;
        }
        return $this->closing_date->isBetween(now(), now()->addDays(30));
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->closing_date) {
            return false;
        }
        return $this->closing_date->isPast() && $this->stage !== 'Closed Won' && $this->stage !== 'Closed Lost';
    }

    public function getStageBadgeClassAttribute()
    {
        return match($this->stage) {
            'Qualification' => 'badge-light-info',
            'Needs Analysis' => 'badge-light-primary',
            'Value Proposition' => 'badge-light-warning',
            'Proposal/Price Quote' => 'badge-light-warning',
            'Negotiation/Review' => 'badge-light-warning',
            'Closed Won' => 'badge-light-success',
            'Closed Lost' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }
}
