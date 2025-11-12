<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zoho_payment_id',
        'zoho_customer_id',
        'zoho_invoice_id',
        'payment_number',
        'payment_date',
        'amount',
        'payment_mode',
        'reference_number',
        'customer_name',
        'customer_id',
        'invoice_id',
        'amount_applied',
        'currency_code',
        'description',
        'bank_charges',
        'tax_account_id',
        'synced_to_zoho',
        'last_synced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'payment_date' => 'date',
        'amount' => 'decimal:2',
        'amount_applied' => 'decimal:2',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Get the customer that owns the payment.
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    /**
     * Get the invoice that owns the payment.
     */
    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Scope a query to only include synced payments.
     */
    public function scopeSynced($query)
    {
        return $query->where('synced_to_zoho', true);
    }

    /**
     * Scope a query to filter by payment mode.
     */
    public function scopeByPaymentMode($query, $mode)
    {
        return $query->where('payment_mode', $mode);
    }

    /**
     * Get payment mode badge color.
     */
    public function getPaymentModeColorAttribute(): string
    {
        return match($this->payment_mode) {
            'cash' => 'success',
            'check' => 'info',
            'creditcard' => 'primary',
            'banktransfer' => 'warning',
            default => 'secondary',
        };
    }
}
