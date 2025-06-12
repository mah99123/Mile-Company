<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('installment_payments', function (Blueprint $table) {
            $table->date('due_date')->nullable()->change();
            $table->enum('status', ['pending', 'paid', 'overdue'])->default('pending')->change();
        });
    }

    public function down(): void
    {
        Schema::table('installment_payments', function (Blueprint $table) {
            $table->date('due_date')->nullable(false)->change();
            $table->string('status')->change();
        });
    }
};
