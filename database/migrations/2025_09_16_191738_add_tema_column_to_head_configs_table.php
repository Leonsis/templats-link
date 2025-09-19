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
        Schema::table('head_configs', function (Blueprint $table) {
            $table->string('tema', 50)->default('global')->after('pagina');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('head_configs', function (Blueprint $table) {
            $table->dropColumn('tema');
        });
    }
};
