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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->unsignedBigInteger('program_id');
            $table->date('fecha_lanzamiento')->nullable();
            $table->date('fecha_convocatoria')->nullable();
            $table->date('fecha_fin_convocatoria')->nullable();
            $table->date('fecha_primera_prueba')->nullable();
            $table->date('fecha_segunda_prueba')->nullable();
            $table->date('fecha_seleccionados')->nullable();
            $table->unsignedInteger('capacidad')->nullable();
            $table->string('imagen')->nullable();
            $table->string('public_id')->nullable();
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->foreign('program_id')->references('id')->on('programs')->onDelete('cascade');
            $table->boolean('publicada')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
