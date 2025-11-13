<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmCall extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_call_id',
        'subject',
        'call_type',
        'call_purpose',
        'call_start_time',
        'call_duration',
        'call_result',
        'related_to_type',
        'related_to_id',
        'related_to_name',
        'who_id_type',
        'who_id',
        'who_name',
        'owner_id',
        'owner_name',
        'description',
        'call_agenda',
        'voice_recording',
        'outgoing_call_status',
        'caller_id',
        'dialled_number',
        'zoho_created_time',
        'zoho_modified_time',
        'synced_to_zoho',
        'last_synced_at',
    ];

    protected $casts = [
        'call_start_time' => 'datetime',
        'zoho_created_time' => 'datetime',
        'zoho_modified_time' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Scopes
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('subject', 'like', "%{$search}%")
              ->orWhere('description', 'like', "%{$search}%")
              ->orWhere('related_to_name', 'like', "%{$search}%")
              ->orWhere('who_name', 'like', "%{$search}%")
              ->orWhere('caller_id', 'like', "%{$search}%")
              ->orWhere('dialled_number', 'like', "%{$search}%");
        });
    }

    public function scopeByCallType($query, $callType)
    {
        return $query->where('call_type', $callType);
    }

    public function scopeByCallResult($query, $callResult)
    {
        return $query->where('call_result', $callResult);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('call_start_time', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('call_start_time', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereMonth('call_start_time', now()->month)
                     ->whereYear('call_start_time', now()->year);
    }

    /**
     * Accessors
     */
    public function getCallTypeBadgeClassAttribute()
    {
        return match($this->call_type) {
            'Outbound' => 'badge-light-primary',
            'Inbound' => 'badge-light-success',
            'Missed' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }

    public function getCallResultBadgeClassAttribute()
    {
        return match($this->call_result) {
            'Interested' => 'badge-light-success',
            'Not Interested' => 'badge-light-danger',
            'No Response' => 'badge-light-warning',
            'Busy' => 'badge-light-info',
            'Wrong Number' => 'badge-light-secondary',
            default => 'badge-light-secondary',
        };
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->call_duration) return '-';

        $minutes = (int) $this->call_duration;
        if ($minutes < 60) {
            return $minutes . ' min';
        }

        $hours = floor($minutes / 60);
        $mins = $minutes % 60;
        return $hours . 'h ' . $mins . 'm';
    }
}
