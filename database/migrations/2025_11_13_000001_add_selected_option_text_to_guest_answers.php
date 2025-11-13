<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('guest_answers', function (Blueprint $table) {
            $table->text('selected_option_text')->nullable()->after('selected_option');
        });
    }

    public function down()
    {
        Schema::table('guest_answers', function (Blueprint $table) {
            $table->dropColumn('selected_option_text');
        });
    }
};
