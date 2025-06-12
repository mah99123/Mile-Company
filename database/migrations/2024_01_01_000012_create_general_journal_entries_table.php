<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('general_journal_entries', function (Blueprint $table) {
            $table->id('entry_id');
            $table->date('date');
            $table->text('description');
            $table->foreignId('debit_account')->constrained('accounts', 'account_id');
            $table->foreignId('credit_account')->constrained('accounts', 'account_id');
            $table->decimal('amount', 15, 2);
            $table->string('reference_number')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('general_journal_entries');
    }
};
