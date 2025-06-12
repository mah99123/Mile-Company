<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('payment_type', ['cash', 'installment'])->default('cash')->after('status');
            $table->integer('quantity')->default(1)->after('product_id');
            $table->decimal('monthly_installment', 10, 2)->nullable()->after('installment_period_months');
        });
    }

    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'quantity', 'monthly_installment']);
        });
    }
};
