<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
// database/migrations/xxxx_xx_xx_xxxxxx_add_puntaje_to_participant_sessions_table.php

public function up()
{
    Schema::table('participant_sessions', function (Blueprint $table) {
        $table->integer('puntaje')->default(0);
    });
}

public function down()
{
    Schema::table('participant_sessions', function (Blueprint $table) {
        $table->dropColumn('puntaje');
    });
}

};
