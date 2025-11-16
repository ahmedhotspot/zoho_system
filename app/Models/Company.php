<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{

    protected $fillable = [
        'name',
        'user_id',
        'contract_type',
        'contract_value',
        'financing_type_id',
        'is_active',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    public function scopeInactive($query)
    {
        return $query->where('is_active', false);
    }

    public function financingType()
    {
        return $this->belongsTo(FinancingType::class,'financing_type_id');
    }



}
