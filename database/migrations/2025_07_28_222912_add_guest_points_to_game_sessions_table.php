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
        $table->integer('guest_points')->default(0);
    });
}


    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('game_sessions', function (Blueprint $table) {
        $table->dropColumn('guest_points');
    });
}

};
