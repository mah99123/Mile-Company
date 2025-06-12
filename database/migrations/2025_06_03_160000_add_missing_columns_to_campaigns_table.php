<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            // Check and add missing columns only if they don't exist
            if (!Schema::hasColumn('campaigns', 'name')) {
                $table->string('name')->nullable()->after('page_name');
            }
            
            if (!Schema::hasColumn('campaigns', 'leads')) {
                $table->integer('leads')->default(0)->after('notes');
            }
            
            if (!Schema::hasColumn('campaigns', 'conversions')) {
                $table->integer('conversions')->default(0)->after('leads');
            }
            
            if (!Schema::hasColumn('campaigns', 'total_cost')) {
                $table->decimal('total_cost', 15, 2)->default(0)->after('conversions');
            }
            
            if (!Schema::hasColumn('campaigns', 'total_profit')) {
                $table->decimal('total_profit', 15, 2)->default(0)->after('total_cost');
            }
        });
    }

    public function down(): void
    {
        Schema::table('campaigns', function (Blueprint $table) {
            $columnsToCheck = ['name', 'leads', 'conversions', 'total_cost', 'total_profit'];
            
            foreach ($columnsToCheck as $column) {
                if (Schema::hasColumn('campaigns', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
