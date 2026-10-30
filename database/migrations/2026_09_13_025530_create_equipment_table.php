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
        Schema::create('equipments', function (Blueprint $table) {
            $table->id();
            // Llave foránea hacia ambientes (environments)
            $table->unsignedBigInteger('environment_id');
            $table->foreign('environment_id')
                    ->references('id')
                    ->on('environments')
                    ->onDelete('cascade');
            $table->string('name');
            $table->string('brand')->nullable();
            $table->string('equipment_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('equipments');
    }
};
