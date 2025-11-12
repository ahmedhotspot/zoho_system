<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Account extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_account_id',
        'account_name',
        'account_code',
        'account_type',
        'description',
        'is_user_created',
        'is_system_account',
        'is_active',
        'parent_account_id',
        'parent_account_name',
        'depth',
        'currency_code',
        'balance',
        'bank_balance',
        'bcy_balance',
        'uncategorized_transactions',
        'is_involved_in_transaction',
        'current_balance',
        'synced_to_zoho',
        'last_synced_at',
    ];

    protected $casts = [
        'is_user_created' => 'boolean',
        'is_system_account' => 'boolean',
        'is_active' => 'boolean',
        'is_involved_in_transaction' => 'boolean',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
        'balance' => 'decimal:2',
        'bank_balance' => 'decimal:2',
        'bcy_balance' => 'decimal:2',
        'uncategorized_transactions' => 'decimal:2',
        'depth' => 'integer',
    ];

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('account_type', $type);
    }

    public function scopeSynced($query)
    {
        return $query->where('synced_to_zoho', true);
    }

    public function scopeUnsynced($query)
    {
        return $query->where('synced_to_zoho', false);
    }

    public function scopeUserCreated($query)
    {
        return $query->where('is_user_created', true);
    }

    public function scopeSystemAccounts($query)
    {
        return $query->where('is_system_account', true);
    }

    // Accessors
    public function getAccountTypeColorAttribute(): string
    {
        return match($this->account_type) {
            'cash', 'bank' => 'success',
            'income', 'other_income' => 'primary',
            'expense', 'cost_of_goods_sold', 'other_expense' => 'danger',
            'other_asset', 'other_current_asset', 'fixed_asset' => 'info',
            'other_current_liability', 'credit_card', 'long_term_liability', 'other_liability' => 'warning',
            'equity' => 'secondary',
            default => 'secondary',
        };
    }

    public function getAccountTypeNameAttribute(): string
    {
        return match($this->account_type) {
            'other_asset' => 'Other Asset',
            'other_current_asset' => 'Other Current Asset',
            'cash' => 'Cash',
            'bank' => 'Bank',
            'fixed_asset' => 'Fixed Asset',
            'other_current_liability' => 'Other Current Liability',
            'credit_card' => 'Credit Card',
            'long_term_liability' => 'Long Term Liability',
            'other_liability' => 'Other Liability',
            'equity' => 'Equity',
            'income' => 'Income',
            'other_income' => 'Other Income',
            'expense' => 'Expense',
            'cost_of_goods_sold' => 'Cost of Goods Sold',
            'other_expense' => 'Other Expense',
            default => ucfirst(str_replace('_', ' ', $this->account_type)),
        };
    }
}
