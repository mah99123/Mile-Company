<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            if (!Schema::hasColumn('campaigns', 'leads_generated')) {
                $table->integer('leads_generated')->default(0)->after('notes');
            }
            if (!Schema::hasColumn('campaigns', 'conversions')) {
                $table->integer('conversions')->default(0)->after('leads_generated');
            }
            if (!Schema::hasColumn('campaigns', 'platform')) {
                $table->string('platform')->default('Facebook')->after('conversions');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $table->dropColumn(['leads_generated', 'conversions', 'platform']);
        });
    }
};
