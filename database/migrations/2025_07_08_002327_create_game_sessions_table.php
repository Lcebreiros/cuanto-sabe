<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_create_game_sessions_table.php
public function up()
{
    Schema::create('game_sessions', function (Blueprint $table) {
        $table->id();
        $table->string('guest_name');
        $table->unsignedBigInteger('motivo_id');
        $table->enum('status', ['active', 'ended'])->default('active');
        $table->timestamps();

        $table->foreign('motivo_id')->references('id')->on('motivos')->onDelete('cascade');
    });
}

};
