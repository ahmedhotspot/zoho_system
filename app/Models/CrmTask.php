<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmTask extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_task_id',
        'subject',
        'due_date',
        'status',
        'priority',
        'related_to_type',
        'related_to_id',
        'related_to_name',
        'contact_id',
        'contact_name',
        'owner_id',
        'owner_name',
        'description',
        'send_notification_email',
        'reminder',
        'repeat',
        'zoho_created_time',
        'zoho_modified_time',
        'closed_time',
        'last_synced_at',
    ];

    protected $casts = [
        'due_date' => 'date',
        'send_notification_email' => 'boolean',
        'repeat' => 'boolean',
        'zoho_created_time' => 'datetime',
        'zoho_modified_time' => 'datetime',
        'closed_time' => 'datetime',
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
              ->orWhere('contact_name', 'like', "%{$search}%");
        });
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByPriority($query, $priority)
    {
        return $query->where('priority', $priority);
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now())
                     ->whereNotIn('status', ['Completed', 'Deferred']);
    }

    public function scopeDueToday($query)
    {
        return $query->whereDate('due_date', today())
                     ->whereNotIn('status', ['Completed', 'Deferred']);
    }

    public function scopeDueThisWeek($query)
    {
        return $query->whereBetween('due_date', [now()->startOfWeek(), now()->endOfWeek()])
                     ->whereNotIn('status', ['Completed', 'Deferred']);
    }

    /**
     * Accessors
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'Not Started' => 'badge-light-secondary',
            'In Progress' => 'badge-light-primary',
            'Completed' => 'badge-light-success',
            'Waiting for input' => 'badge-light-warning',
            'Deferred' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }

    public function getPriorityBadgeClassAttribute()
    {
        return match($this->priority) {
            'Highest' => 'badge-light-danger',
            'High' => 'badge-light-warning',
            'Normal' => 'badge-light-primary',
            'Low' => 'badge-light-info',
            'Lowest' => 'badge-light-secondary',
            default => 'badge-light-secondary',
        };
    }

    public function getIsOverdueAttribute()
    {
        if (!$this->due_date) return false;
        return $this->due_date->isPast() &&
               !in_array($this->status, ['Completed', 'Deferred']);
    }

    public function getIsDueTodayAttribute()
    {
        if (!$this->due_date) return false;
        return $this->due_date->isToday() &&
               !in_array($this->status, ['Completed', 'Deferred']);
    }
}
