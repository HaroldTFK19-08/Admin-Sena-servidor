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
        Schema::create('registrations', function (Blueprint $table) {
            $table->id();
            // Llaves foráneas explícitas
            $table->unsignedBigInteger('aspirant_id');
            $table->foreign('aspirant_id')
                    ->references('id')
                    ->on('aspirants')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('offer_id');
            $table->foreign('offer_id')
                    ->references('id')
                    ->on('offers')
                    ->onDelete('cascade');
            // Estado del proceso de inscripción
            $table->enum('estado', ["Registrado", "En proceso", "Seleccionado", "Rechazado"])->default('Registrado'); // registered, in_process, selected, rejected
            $table->timestamp('Fecha_inscripcion')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('registrations');
    }
};
