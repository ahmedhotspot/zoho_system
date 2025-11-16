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
    ];

    public function company()
    {
        return $this->belongsTo(Companie::class, 'company_id');
    }

    public function financing_type()
    {
        return $this->belongsTo(Companie::class, 'financing_type_id');
    }
}
