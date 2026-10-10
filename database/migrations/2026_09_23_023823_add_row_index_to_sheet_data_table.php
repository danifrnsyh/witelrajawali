<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sheet_data', function (Blueprint $table) {
            $table->integer('row_index')->nullable()->after('id');
        });
        Schema::table('dashboard_data', function (Blueprint $table) {
            $table->integer('row_index')->nullable()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sheet_data', function (Blueprint $table) {
            $table->dropColumn('row_index');
        });
        Schema::table('dashboard_data', function (Blueprint $table) {
            $table->dropColumn('row_index');
        });
    }
};
