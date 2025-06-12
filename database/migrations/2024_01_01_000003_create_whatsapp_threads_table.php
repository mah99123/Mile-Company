<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('whatsapp_threads', function (Blueprint $table) {
            $table->id('thread_id');
            $table->foreignId('campaign_id')->constrained('campaigns', 'campaign_id')->onDelete('cascade');
            $table->string('customer_whatsapp');
            $table->text('message_content');
            $table->timestamp('message_date');
            $table->enum('message_type', ['sent', 'received'])->default('sent');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('whatsapp_threads');
    }
};
