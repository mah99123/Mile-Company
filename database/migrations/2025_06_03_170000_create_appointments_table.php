<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('title'); // عنوان الموعد
            $table->text('description')->nullable(); // وصف الموعد
            $table->dateTime('appointment_date'); // تاريخ ووقت الموعد
            $table->string('location')->nullable(); // مكان الموعد
            $table->string('attendees')->nullable(); // الحضور
            $table->enum('priority', ['low', 'medium', 'high'])->default('medium'); // الأولوية
            $table->enum('status', ['scheduled', 'completed', 'cancelled', 'postponed'])->default('scheduled'); // الحالة
            $table->integer('reminder_minutes')->default(30); // تذكير قبل كم دقيقة
            $table->boolean('reminder_sent')->default(false); // هل تم إرسال التذكير
            $table->string('reminder_type')->default('notification'); // نوع التذكير
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // المستخدم المسؤول
            $table->foreignId('created_by')->constrained('users')->onDelete('cascade'); // من أنشأ الموعد
            $table->json('reminder_settings')->nullable(); // إعدادات التذكير
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointments');
    }
};
