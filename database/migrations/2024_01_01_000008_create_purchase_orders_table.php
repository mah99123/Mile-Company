<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id('order_id');
            $table->foreignId('supplier_id')->constrained('suppliers', 'supplier_id');
            $table->date('order_date');
            $table->date('expected_delivery_date');
            $table->enum('status', ['Ordered', 'Delivered', 'Cancelled'])->default('Ordered');
            $table->decimal('total_order_value', 15, 2);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
