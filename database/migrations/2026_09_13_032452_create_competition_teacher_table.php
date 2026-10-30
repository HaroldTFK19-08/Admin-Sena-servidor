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
        Schema::create('competition_teacher', function (Blueprint $table) {
            $table->id();
            // Llaves foráneas explícitas
            $table->unsignedBigInteger('competency_id');
            $table->foreign('competency_id')
                    ->references('id')
                    ->on('competitions')
                    ->onDelete('cascade');
            // Llave foránea hacia instructores
            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')
                    ->references('id')
                    ->on('teachers')
                    ->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('competition_teacher');
    }
};
