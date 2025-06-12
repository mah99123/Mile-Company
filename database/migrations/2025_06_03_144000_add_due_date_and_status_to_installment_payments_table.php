<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
  public function up(): void
{
    Schema::table('installment_payments', function (Blueprint $table) {
        if (! Schema::hasColumn('installment_payments', 'due_date')) {
            $table->date('due_date')->after('payment_date');
        }

        if (! Schema::hasColumn('installment_payments', 'status')) {
            $table->enum('status', ['pending', 'paid', 'overdue'])
                  ->default('pending')
                  ->after('amount_paid');
        }
    });
}

public function down(): void
{
    Schema::table('installment_payments', function (Blueprint $table) {
        if (Schema::hasColumn('installment_payments', 'due_date')) {
            $table->dropColumn('due_date');
        }
        if (Schema::hasColumn('installment_payments', 'status')) {
            $table->dropColumn('status');
        }
    });
}

};
