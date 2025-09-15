<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('game_sessions', function (Blueprint $table) {
        $table->json('pregunta_json')->nullable();
    });
}

public function down()
{
    Schema::table('game_sessions', function (Blueprint $table) {
        $table->dropColumn('pregunta_json');
    });
}

};
