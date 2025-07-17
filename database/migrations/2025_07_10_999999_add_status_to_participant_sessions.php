<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStatusToParticipantSessions extends Migration
{
    public function up()
    {
        Schema::table('participant_sessions', function (Blueprint $table) {
            // Ajustá los estados según los que uses en tu código
            $table->enum('status', ['pending', 'waiting', 'active', 'playing'])
                ->default('pending')
                ->after('dni_last4');
        });
    }

    public function down()
    {
        Schema::table('participant_sessions', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
}
