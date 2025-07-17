<?php

// database/migrations/xxxx_xx_xx_create_participant_sessions_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateParticipantSessionsTable extends Migration
{
    public function up()
    {
    Schema::create('participant_sessions', function (Blueprint $table) {
        $table->id();
        $table->foreignId('game_session_id')->constrained('game_sessions')->onDelete('cascade');
        $table->string('username');
        $table->string('dni_last4', 4);
        $table->unsignedInteger('order');
        $table->string('status')->default('pending');
        $table->timestamps();
    });
    
    }

    public function down()
    {
        Schema::dropIfExists('participant_sessions');
    }
}
