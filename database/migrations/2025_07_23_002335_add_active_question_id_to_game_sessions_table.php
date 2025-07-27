<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->unsignedBigInteger('active_question_id')->nullable()->after('motivo_id');

            // Si querés la FK estricta, descomenta la siguiente línea:
            // $table->foreign('active_question_id')->references('id')->on('questions')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            // Si pusiste FK, primero dropeala:
            // $table->dropForeign(['active_question_id']);
            $table->dropColumn('active_question_id');
        });
    }
};

