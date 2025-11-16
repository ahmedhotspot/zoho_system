<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class FinancingType extends Model
{
        use HasTranslations;

    protected $fillable = [
        'name',
        'is_active',
    ];

        public array $translatable = ['name'];

}
