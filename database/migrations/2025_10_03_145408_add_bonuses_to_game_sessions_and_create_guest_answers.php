<?php

// database/migrations/2025_10_03_000001_add_bonuses_to_game_sessions_and_create_guest_answers.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBonusesToGameSessionsAndCreateGuestAnswers extends Migration
{
    public function up()
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->boolean('apuesta_x2_active')->default(false)->after('guest_points');
            $table->unsignedTinyInteger('descarte_usados')->default(0)->after('apuesta_x2_active');
        });

        Schema::create('guest_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->constrained('game_sessions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->boolean('is_correct')->default(false);
            $table->integer('points_awarded')->default(0);
            $table->boolean('apuesta_x2')->default(false);
            $table->boolean('was_discarded')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('guest_answers');

        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropColumn(['apuesta_x2_active', 'descarte_usados']);
        });
    }
}

