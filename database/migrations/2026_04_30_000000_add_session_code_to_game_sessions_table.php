<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use App\Models\GameSession;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->string('session_code', 8)->nullable()->after('status');
        });

        // Asignar código a sesiones activas existentes
        GameSession::where('status', 'active')->whereNull('session_code')->each(function ($s) {
            $s->session_code = strtoupper(Str::random(6));
            $s->save();
        });
    }

    public function down(): void
    {
        Schema::table('game_sessions', function (Blueprint $table) {
            $table->dropColumn('session_code');
        });
    }
};
