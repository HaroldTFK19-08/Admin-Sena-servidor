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
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            // Llaves foráneas explícitas
            $table->unsignedBigInteger('equipment_id');
            $table->foreign('equipment_id')
                    ->references('id')
                    ->on('equipments')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('apprentice_id');
            $table->foreign('apprentice_id')
                    ->references('id')
                    ->on('apprentices')
                    ->onDelete('cascade');
            // Atributos de seguimiento de la asignación
            $table->timestamp('fecha_asignacion')->useCurrent();
            $table->timestamp('fecha_devolucion')->nullable();
            $table->enum('Estado',["Activo","Inactivo"])->default('Activo');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
