<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'po_item_id';

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity_ordered',
        'cost_per_unit',
    ];

    protected $casts = [
        'cost_per_unit' => 'decimal:2',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function getTotalCostAttribute()
    {
        return $this->quantity_ordered * $this->cost_per_unit;
    }
}
