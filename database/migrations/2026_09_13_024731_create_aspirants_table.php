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
        Schema::create('aspirants', function (Blueprint $table) {
            $table->id();
            // Columna para la llave foránea
            $table->unsignedBigInteger('user_id')->unique();
            // Definición explícita de la relación
            $table->foreign('user_id')
                    ->references('id')
                    ->on('users')
                    ->onDelete('cascade');
            $table->enum('eps', [
                'SURA',
                'Sanitas',
                'Nueva EPS',
                'Salud Total',
                'Compensar',
                'Coosalud',
                'Emssanar',
            ]);
            $table->string('certificado_eps')->nullable();
            $table->string('certificado_bachiller')->nullable();
            $table->string('certificado_icfes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('aspirants');
    }
};
