<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guest_answers', function (Blueprint $table) {
            $table->string('selected_option', 1)->nullable()->after('question_id');
            $table->string('correct_option', 1)->nullable()->after('selected_option');
        });
    }

    public function down()
    {
        Schema::table('guest_answers', function (Blueprint $table) {
            $table->dropColumn(['selected_option', 'correct_option']);
        });
    }
};
