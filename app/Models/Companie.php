<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Companie extends Model
{

    protected $fillable = [
        'name',
        'user_id',
        'contract_type',
        'contract_value',
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



}
