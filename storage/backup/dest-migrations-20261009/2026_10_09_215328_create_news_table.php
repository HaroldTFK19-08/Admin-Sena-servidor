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
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('admin_id');
            $table->foreign('admin_id')
                    ->references('id')
                    ->on('admins')
                    ->onDelete('cascade');
            $table->unsignedBigInteger('trainingcenter_id');
            $table->foreign('trainingcenter_id')
                    ->references('id')
                    ->on('trainingcenters')
                    ->onDelete('cascade');
            $table->string('titulo');
            $table->text('descripcion');
            $table->string('imagen')->nullable();
            $table->enum('estado',["Creado","Pendiente","Publicado"])->default("Creado");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
