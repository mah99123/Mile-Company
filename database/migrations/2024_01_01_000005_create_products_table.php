<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('product_id');
            $table->string('name');
            $table->enum('category', ['Phone', 'Accessory', 'Electronics']);
            $table->decimal('cost_price', 10, 2);
            $table->decimal('selling_price', 10, 2);
            $table->integer('quantity_in_stock')->default(0);
            $table->integer('reorder_threshold')->default(10);
            $table->string('sku')->unique();
            $table->text('description')->nullable();
            $table->foreignId('supplier_id')->constrained('suppliers', 'supplier_id');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
            
            $table->index('category');
            $table->index('quantity_in_stock');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
