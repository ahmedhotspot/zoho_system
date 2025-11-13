<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmEvent extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_event_id',
        'event_title',
        'start_datetime',
        'end_datetime',
        'venue',
        'location',
        'related_to_type',
        'related_to_id',
        'related_to_name',
        'participants',
        'contact_name',
        'contact_id',
        'owner_id',
        'owner_name',
        'description',
        'send_notification',
        'reminder',
        'check_in_status',
        'check_in_address',
        'check_in_time',
        'check_in_sub_locality',
        'check_in_city',
        'check_in_state',
        'check_in_country',
        'is_recurring',
        'recurring_activity',
        'zoho_created_time',
        'zoho_modified_time',
        'synced_to_zoho',
        'last_synced_at',
    ];

    protected $casts = [
        'start_datetime' => 'datetime',
        'end_datetime' => 'datetime',
        'send_notification' => 'boolean',
        'check_in_status' => 'boolean',
        'check_in_time' => 'datetime',
        'is_recurring' => 'boolean',
        'zoho_created_time' => 'datetime',
        'zoho_modified_time' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    // Scopes
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('event_title', 'like', "%{$search}%")
                ->orWhere('venue', 'like', "%{$search}%")
                ->orWhere('location', 'like', "%{$search}%")
                ->orWhere('related_to_name', 'like', "%{$search}%")
                ->orWhere('contact_name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        });
    }

    public function scopeUpcoming($query)
    {
        return $query->where('start_datetime', '>=', now())
            ->orderBy('start_datetime', 'asc');
    }

    public function scopePast($query)
    {
        return $query->where('start_datetime', '<', now())
            ->orderBy('start_datetime', 'desc');
    }

    public function scopeToday($query)
    {
        return $query->whereDate('start_datetime', today());
    }

    public function scopeThisWeek($query)
    {
        return $query->whereBetween('start_datetime', [
            now()->startOfWeek(),
            now()->endOfWeek()
        ]);
    }

    public function scopeThisMonth($query)
    {
        return $query->whereYear('start_datetime', now()->year)
            ->whereMonth('start_datetime', now()->month);
    }

    // Accessors
    public function getStatusBadgeClassAttribute()
    {
        if (!$this->start_datetime) {
            return 'badge-light-secondary';
        }

        if ($this->start_datetime->isFuture()) {
            return 'badge-light-primary'; // Upcoming
        } elseif ($this->start_datetime->isToday()) {
            return 'badge-light-success'; // Today
        } else {
            return 'badge-light-secondary'; // Past
        }
    }

    public function getStatusTextAttribute()
    {
        if (!$this->start_datetime) {
            return 'No Date';
        }

        if ($this->start_datetime->isFuture()) {
            return 'Upcoming';
        } elseif ($this->start_datetime->isToday()) {
            return 'Today';
        } else {
            return 'Past';
        }
    }

    public function getFormattedDurationAttribute()
    {
        if (!$this->start_datetime || !$this->end_datetime) {
            return '-';
        }

        $duration = $this->start_datetime->diffInMinutes($this->end_datetime);

        if ($duration < 60) {
            return $duration . ' min';
        }

        $hours = floor($duration / 60);
        $mins = $duration % 60;

        if ($mins > 0) {
            return $hours . 'h ' . $mins . 'm';
        }

        return $hours . 'h';
    }
}
