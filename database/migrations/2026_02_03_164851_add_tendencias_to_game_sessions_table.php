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
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->integer('tendencias_acertadas')->default(0)->after('descarte_usados');
            $table->integer('tendencias_objetivo')->default(10)->after('tendencias_acertadas');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropColumn(['tendencias_acertadas', 'tendencias_objetivo']);
        });
    }
};
