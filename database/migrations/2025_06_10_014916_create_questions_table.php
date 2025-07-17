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
// database/migrations/xxxx_create_questions_table.php
Schema::create('questions', function (Blueprint $table) {
    $table->id();
    $table->string('text');
    $table->json('options'); // ["op1", "op2", ...]
    $table->integer('correct_index'); // índice de la opción correcta
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
