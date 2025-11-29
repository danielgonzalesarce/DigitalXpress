<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Agrega el campo is_active para habilitar/deshabilitar reparaciones
     * sin eliminarlas de la base de datos.
     */
    public function up(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            // Agregar campo is_active solo si no existe
            if (!Schema::hasColumn('repairs', 'is_active')) {
                $table->boolean('is_active')->default(true)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('repairs', function (Blueprint $table) {
            // Eliminar el campo is_active si existe
            if (Schema::hasColumn('repairs', 'is_active')) {
                $table->dropColumn('is_active');
            }
        });
    }
};
