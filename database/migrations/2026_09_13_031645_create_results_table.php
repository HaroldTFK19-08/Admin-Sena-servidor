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
        Schema::create('results', function (Blueprint $table) {
            $table->id();
            // Llave foránea hacia competencias (competencies)
            $table->unsignedBigInteger('competition_id');
            $table->foreign('competition_id')
                    ->references('id')
                    ->on('competitions')
                    ->onDelete('cascade');
            // Llave foránea hacia aprendices (apprentices)
            $table->unsignedBigInteger('apprentice_id');
            $table->foreign('apprentice_id')
                    ->references('id')
                    ->on('apprentices')
                    ->onDelete('cascade');
            $table->enum('estado_resultado',["Aprobado","Reprobado","Pendiente"])->default('Pendiente'); // Ej: approved, unapproved, pending
            $table->text('comentarios')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('results');
    }
};
