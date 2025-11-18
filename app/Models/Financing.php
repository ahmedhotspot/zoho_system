<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Financing extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'iqama_number',
        'application_id',
        'financingcompanies',
        'price',
        'company_id',
        'financing_type_id',
        'application_uuid'
    ];

        protected $casts = [
        'price' => 'decimal:2',
        'created_at' => 'datetime',

    ];

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'user_id');
    }

    public function financingType()
    {
        return $this->belongsTo(Company::class, 'financing_type_id');
    }

    public function priceHistories()
    {
        return $this->hasMany(FinancingPriceHistory::class);
    }
}
