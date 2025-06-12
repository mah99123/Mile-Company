<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id('invoice_id');
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->foreignId('product_id')->constrained('products', 'product_id');
            $table->date('sale_date');
            $table->decimal('total_amount', 10, 2);
            $table->decimal('down_payment', 10, 2);
            $table->decimal('remaining_amount', 10, 2);
            $table->decimal('installment_amount', 10, 2);
            $table->integer('installment_period_months');
            $table->integer('installments_paid')->default(0);
            $table->date('next_installment_due_date');
            $table->enum('status', ['Pending', 'Active', 'Completed', 'Overdue'])->default('Pending');
            $table->foreignId('created_by')->constrained('users');
            $table->timestamps();
            
            $table->index('status');
            $table->index('next_installment_due_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sales');
    }
};
