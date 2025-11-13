<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CrmNote extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'zoho_note_id',
        'note_title',
        'note_content',
        'parent_module',
        'parent_id',
        'parent_name',
        'owner_id',
        'owner_name',
        'created_by_id',
        'created_by_name',
        'modified_by_id',
        'modified_by_name',
        'zoho_created_time',
        'zoho_modified_time',
        'last_synced_at',
    ];

    protected $casts = [
        'zoho_created_time' => 'datetime',
        'zoho_modified_time' => 'datetime',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Scope: Search notes
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('note_title', 'like', "%{$search}%")
              ->orWhere('note_content', 'like', "%{$search}%")
              ->orWhere('parent_name', 'like', "%{$search}%");
        });
    }

    /**
     * Scope: Filter by parent module
     */
    public function scopeByParentModule($query, $module)
    {
        return $query->where('parent_module', $module);
    }

    /**
     * Scope: Filter by parent ID
     */
    public function scopeByParentId($query, $parentId)
    {
        return $query->where('parent_id', $parentId);
    }

    /**
     * Scope: Recent notes (last 30 days)
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', now()->subDays(30));
    }

    /**
     * Accessor: Get parent module badge class
     */
    public function getParentModuleBadgeClassAttribute()
    {
        return match($this->parent_module) {
            'Leads' => 'badge-light-primary',
            'Contacts' => 'badge-light-success',
            'Deals' => 'badge-light-warning',
            'Accounts' => 'badge-light-info',
            'Tasks' => 'badge-light-danger',
            default => 'badge-light-secondary',
        };
    }

    /**
     * Accessor: Get truncated note content
     */
    public function getTruncatedContentAttribute()
    {
        if (!$this->note_content) {
            return '-';
        }

        return strlen($this->note_content) > 100
            ? substr($this->note_content, 0, 100) . '...'
            : $this->note_content;
    }
}
