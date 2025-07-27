<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantAnswersTable extends Migration
{
public function up()
{
    Schema::dropIfExists('participant_answers');
    Schema::create('participant_answers', function (Blueprint $table) {
        $table->id();
        $table->unsignedBigInteger('participant_session_id');
        $table->unsignedBigInteger('question_id');
        $table->string('option_label', 2); // 'A', 'B', 'C', 'D'
        $table->timestamps();

        $table->foreign('participant_session_id')
            ->references('id')->on('participant_sessions')
            ->onDelete('cascade');

        $table->foreign('question_id')
            ->references('id')->on('questions')
            ->onDelete('cascade');

        $table->unique(['participant_session_id', 'question_id']);
    });
}


    public function down()
    {
        Schema::dropIfExists('participant_answers');
    }
}
