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
            // Agregamos la columna modo_juego con valor por defecto 'normal'
            $table->enum('modo_juego', ['normal', 'express'])
                  ->default('normal')
                  ->after('guest_points'); // ajusta 'after' según tus columnas
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropColumn('modo_juego');
        });
    }
};
