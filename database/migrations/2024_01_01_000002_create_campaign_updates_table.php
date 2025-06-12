<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('campaign_updates', function (Blueprint $table) {
            $table->id('update_id');
            $table->foreignId('campaign_id')->constrained('campaigns', 'campaign_id')->onDelete('cascade');
            $table->text('update_text');
            $table->timestamp('update_date');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('campaign_updates');
    }
};
