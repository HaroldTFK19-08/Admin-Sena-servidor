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
        Schema::create('offerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')->references('id')->on('admins')->cascadeOnDelete();
            $table->unsignedBigInteger('program_id');
            $table->foreign('program_id')->references('id')->on('programs')->cascadeOnDelete();
            $table->date('fecha_lanzamiento');
            $table->integer('capacidad');
            $table->date('fecha_convocatoria');
            $table->date('fecha_fin_convocatoria');
            $table->date('fecha_primera_prueba')->nullable();
            $table->date('fecha_segunda_prueba')->nullable();
            $table->date('fecha_seleccionados')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('oferts');
    }
};
