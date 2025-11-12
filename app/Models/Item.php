<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'zoho_item_id',
        'name',
        'sku',
        'description',
        'item_type',
        'product_type',
        'rate',
        'purchase_rate',
        'tax_id',
        'tax_name',
        'tax_percentage',
        'tax_type',
        'account_id',
        'account_name',
        'purchase_account_id',
        'purchase_account_name',
        'inventory_account_id',
        'inventory_account_name',
        'initial_stock',
        'stock_on_hand',
        'reorder_level',
        'unit',
        'status',
        'is_taxable',
        'is_returnable',
        'synced_to_zoho',
        'last_synced_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'rate' => 'decimal:2',
        'purchase_rate' => 'decimal:2',
        'tax_percentage' => 'decimal:2',
        'initial_stock' => 'decimal:2',
        'stock_on_hand' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'is_taxable' => 'boolean',
        'is_returnable' => 'boolean',
        'synced_to_zoho' => 'boolean',
        'last_synced_at' => 'datetime',
    ];

    /**
     * Scope a query to only include active items.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include sales items.
     */
    public function scopeSales($query)
    {
        return $query->whereIn('item_type', ['sales', 'sales_and_purchases']);
    }

    /**
     * Scope a query to only include purchase items.
     */
    public function scopePurchases($query)
    {
        return $query->whereIn('item_type', ['purchases', 'sales_and_purchases']);
    }

    /**
     * Scope a query to only include inventory items.
     */
    public function scopeInventory($query)
    {
        return $query->where('item_type', 'inventory');
    }

    /**
     * Scope a query to only include synced items.
     */
    public function scopeSynced($query)
    {
        return $query->where('synced_to_zoho', true);
    }

    /**
     * Get the status color for badge.
     */
    public function getStatusColorAttribute()
    {
        return $this->status === 'active' ? 'success' : 'danger';
    }

    /**
     * Get the item type color for badge.
     */
    public function getItemTypeColorAttribute()
    {
        return match($this->item_type) {
            'sales' => 'primary',
            'purchases' => 'warning',
            'sales_and_purchases' => 'info',
            'inventory' => 'success',
            default => 'secondary'
        };
    }

    /**
     * Get the product type color for badge.
     */
    public function getProductTypeColorAttribute()
    {
        return $this->product_type === 'goods' ? 'primary' : 'info';
    }

    /**
     * Check if item is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Check if item is taxable
     */
    public function isTaxable(): bool
    {
        return $this->is_taxable;
    }

    /**
     * Check if item is in stock (for inventory items)
     */
    public function isInStock(): bool
    {
        if ($this->item_type !== 'inventory') {
            return true; // Non-inventory items are always "in stock"
        }

        return $this->stock_on_hand > 0;
    }

    /**
     * Check if item needs reorder
     */
    public function needsReorder(): bool
    {
        if ($this->item_type !== 'inventory' || !$this->reorder_level) {
            return false;
        }

        return $this->stock_on_hand <= $this->reorder_level;
    }
}
