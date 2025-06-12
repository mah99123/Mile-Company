<?php

namespace App\Observers;

use App\Models\Product;
use App\Services\NotificationService;

class ProductObserver
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function updated(Product $product)
    {
        // تحقق من المخزون المنخفض
        if ($product->wasChanged('quantity_in_stock')) {
            if ($product->quantity_in_stock <= 0) {
                $this->notificationService->outOfStock($product);
            } elseif ($product->quantity_in_stock <= $product->reorder_threshold) {
                $this->notificationService->lowStock($product);
            }
        }
    }
}
