<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id('campaign_id');
            $table->string('page_name');
            $table->string('owner_name');
            $table->string('specialization');
            $table->decimal('budget_total', 15, 2);
            $table->date('start_date');
            $table->date('end_date');
            $table->date('launch_date');
            $table->date('stop_date')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->constrained('users');
            $table->enum('status', ['active', 'paused', 'completed', 'cancelled'])->default('active');
            $table->timestamps();
            
            $table->index(['start_date', 'end_date']);
            $table->index('specialization');
            $table->index('status');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
