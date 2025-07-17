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
    Schema::table('questions', function (Blueprint $table) {
        // Cambiamos 'text' a 'texto' (opcional, pero para seguir el estándar que usamos)
        if (Schema::hasColumn('questions', 'text')) {
            $table->renameColumn('text', 'texto');
        }

        // Si usás 'options' en formato JSON y querés columnas separadas:
        if (Schema::hasColumn('questions', 'options')) {
            $table->dropColumn('options');
        }

        // Opciones separadas (si no existen, crealas)
        if (!Schema::hasColumn('questions', 'opcion_correcta')) {
            $table->string('opcion_correcta')->nullable();
        }
        if (!Schema::hasColumn('questions', 'opcion_1')) {
            $table->string('opcion_1')->nullable();
        }
        if (!Schema::hasColumn('questions', 'opcion_2')) {
            $table->string('opcion_2')->nullable();
        }
        if (!Schema::hasColumn('questions', 'opcion_3')) {
            $table->string('opcion_3')->nullable();
        }

        // Campo para pregunta activa
        if (!Schema::hasColumn('questions', 'is_active')) {
            $table->boolean('is_active')->default(false);
        }
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('questions', function (Blueprint $table) {
            //
        });
    }
};
