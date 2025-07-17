<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_update_users_for_dni_login.php
public function up(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['email', 'password']);
        $table->string('dni_ultimo4', 4)->after('name');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->string('email')->unique();
        $table->string('password');
        $table->dropColumn('dni_ultimo4');
    });
}

};
