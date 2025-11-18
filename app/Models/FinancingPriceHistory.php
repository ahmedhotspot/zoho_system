<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancingPriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'financing_id',
        'company_id',
        'user_id',
        'old_price',
        'new_price',
        'notes',
    ];

    protected $casts = [
        'old_price' => 'decimal:2',
        'new_price' => 'decimal:2',
    ];

    /**
     * Get the financing that owns the price history.
     */
    public function financing()
    {
        return $this->belongsTo(Financing::class);
    }

    /**
     * Get the company associated with the price history.
     */
    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'user_id');
    }

    /**
     * Get the user who made the price update.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

