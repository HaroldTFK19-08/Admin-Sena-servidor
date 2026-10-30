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
        Schema::create('environments', function (Blueprint $table) {
            $table->id();
            // Columna de la llave foránea manteniendo tu nombre 'id_centro'
            $table->unsignedBigInteger('trainingcenter_id');
            // Definición explícita de la relación
            $table->foreign('trainingcenter_id')
                    ->references('id')
                    ->on('trainingcenters')
                    ->onDelete('cascade');
            $table->string('nombre');
            $table->integer('capacidad');
            $table->enum('tipo_ambiente',["Software","TICS","Laboratorio","Cocina"]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('environments');
    }
};
