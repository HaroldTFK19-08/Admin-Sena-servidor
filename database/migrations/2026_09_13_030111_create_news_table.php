<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id();
            $table->foreignId('trainingcenter_id')->constrained('trainingcenters')->cascadeOnDelete();
            $table->foreignId('admin_id')->nullable()->constrained('admins')->nullOnDelete();
            $table->string('titulo', 255);
            $table->text('descripcion');
            $table->string('imagen')->nullable();
            $table->enum('estado', ['Creado', 'Pendiente', 'Publicado'])->default('Creado');
            $table->enum('categoria',['Tecnologia','Educacion','Deporte','Politica','Artistica','Diseño']);
            $table->boolean('publicada')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
