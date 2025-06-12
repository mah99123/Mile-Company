<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تحديث جدول الإشعارات الموجود
        Schema::table('notifications', function (Blueprint $table) {
            // إضافة الأعمدة الجديدة إذا لم تكن موجودة
            if (!Schema::hasColumn('notifications', 'type')) {
                $table->string('type')->after('id');
            }
            if (!Schema::hasColumn('notifications', 'title')) {
                $table->string('title')->after('type');
            }
            if (!Schema::hasColumn('notifications', 'message')) {
                $table->text('message')->after('title');
            }
            if (!Schema::hasColumn('notifications', 'data')) {
                $table->json('data')->nullable()->after('message');
            }
            if (!Schema::hasColumn('notifications', 'user_id')) {
                $table->foreignId('user_id')->constrained()->onDelete('cascade')->after('data');
            }
            if (!Schema::hasColumn('notifications', 'is_read')) {
                $table->boolean('is_read')->default(false)->after('user_id');
            }
            if (!Schema::hasColumn('notifications', 'read_at')) {
                $table->timestamp('read_at')->nullable()->after('is_read');
            }
            if (!Schema::hasColumn('notifications', 'icon')) {
                $table->string('icon')->nullable()->after('read_at');
            }
            if (!Schema::hasColumn('notifications', 'color')) {
                $table->string('color')->default('primary')->after('icon');
            }
            if (!Schema::hasColumn('notifications', 'sound')) {
                $table->string('sound')->nullable()->after('color');
            }
        });
    }

    public function down(): void
    {
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropColumn([
                'type', 'title', 'message', 'data', 'user_id', 
                'is_read', 'read_at', 'icon', 'color', 'sound'
            ]);
        });
    }
};
