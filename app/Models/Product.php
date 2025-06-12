<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $primaryKey = 'product_id';

    protected $fillable = [
        'name',
        'category',
        'cost_price',
        'selling_price',
        'quantity_in_stock',
        'reorder_threshold',
        'sku',
        'description',
        'supplier_id',
        'status',
    ];

    protected $casts = [
        'cost_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
    ];

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    public function sales()
    {
        return $this->hasMany(Sale::class, 'product_id');
    }

    public function purchaseOrderItems()
    {
        return $this->hasMany(PurchaseOrderItem::class, 'product_id');
    }

    public function getProfitMarginAttribute()
    {
        return $this->selling_price - $this->cost_price;
    }

    public function getIsLowStockAttribute()
    {
        return $this->quantity_in_stock <= $this->reorder_threshold;
    }
}
