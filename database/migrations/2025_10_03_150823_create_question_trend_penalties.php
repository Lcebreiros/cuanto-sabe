<?php

// database/migrations/xxxx_create_question_trend_penalties.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuestionTrendPenalties extends Migration
{
    public function up()
    {
        Schema::create('question_trend_penalties', function (Blueprint $table) {
            $table->id();
            $table->foreignId('game_session_id')->constrained('game_sessions')->onDelete('cascade');
            $table->foreignId('question_id')->constrained('questions')->onDelete('cascade');
            $table->integer('penalty_count')->default(0);
            $table->timestamps();
            $table->unique(['game_session_id','question_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('question_trend_penalties');
    }
}
