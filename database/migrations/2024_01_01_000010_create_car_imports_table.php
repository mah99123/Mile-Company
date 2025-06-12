<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('car_imports', function (Blueprint $table) {
            $table->id();
            $table->string('auction_type');
            $table->string('lot_number');
            $table->decimal('total_with_transfer', 15, 2);
            $table->decimal('amount_received', 15, 2);
            $table->decimal('remaining_amount', 15, 2);
            $table->date('auction_invoice_date');
            $table->string('auction_invoice_number');
            $table->string('office_contract_number');
            $table->date('office_contract_date');
            $table->decimal('office_invoice_amount', 15, 2);
            $table->decimal('company_shipping_cost', 15, 2);
            $table->decimal('customer_shipping_cost', 15, 2);
            $table->decimal('remaining_office_invoice', 15, 2);
            $table->decimal('shipping_profit', 15, 2);
            $table->decimal('office_commission', 15, 2);
            $table->string('currency');
            $table->text('notes')->nullable();
            $table->foreignId('employee_assigned')->constrained('users');
            $table->enum('shipping_status', ['Pending', 'Shipped', 'Arrived', 'Delivered'])->default('Pending');
            $table->string('shipping_company');
            $table->string('buyer_company');
            $table->date('pull_date')->nullable();
            $table->date('shipping_date')->nullable();
            $table->date('arrival_date')->nullable();
            $table->string('container_number')->nullable();
            $table->string('recipient_name')->nullable();
            $table->date('recipient_receive_date')->nullable();
            $table->string('recipient_phone')->nullable();
            $table->timestamps();
            
            $table->index('shipping_status');
            $table->index('lot_number');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('car_imports');
    }
};
