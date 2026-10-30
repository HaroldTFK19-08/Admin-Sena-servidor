<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            Schema::disableForeignKeyConstraints();

            Schema::create('registrations_with_cancelada', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aspirant_id')->constrained('aspirants')->cascadeOnDelete();
                $table->foreignId('offer_id')->constrained('offers')->cascadeOnDelete();
                $table->enum('estado', ['Registrado', 'En proceso', 'Seleccionado', 'Rechazado', 'Cancelada'])->default('Registrado');
                $table->timestamp('Fecha_inscripcion')->useCurrent();
                $table->timestamps();
            });

            DB::table('registrations')->get()->each(function ($registro) {
                DB::table('registrations_with_cancelada')->insert((array) $registro);
            });

            Schema::dropIfExists('registrations');
            Schema::rename('registrations_with_cancelada', 'registrations');
            Schema::enableForeignKeyConstraints();

            return;
        }

        DB::statement("ALTER TABLE registrations MODIFY COLUMN estado ENUM('Registrado','En proceso','Seleccionado','Rechazado','Cancelada') NOT NULL DEFAULT 'Registrado'");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'sqlite') {
            DB::table('registrations')->where('estado', 'Cancelada')->update(['estado' => 'Rechazado']);
            Schema::disableForeignKeyConstraints();

            Schema::create('registrations_without_cancelada', function (Blueprint $table) {
                $table->id();
                $table->foreignId('aspirant_id')->constrained('aspirants')->cascadeOnDelete();
                $table->foreignId('offer_id')->constrained('offers')->cascadeOnDelete();
                $table->enum('estado', ['Registrado', 'En proceso', 'Seleccionado', 'Rechazado'])->default('Registrado');
                $table->timestamp('Fecha_inscripcion')->useCurrent();
                $table->timestamps();
            });

            DB::table('registrations')->get()->each(function ($registro) {
                DB::table('registrations_without_cancelada')->insert((array) $registro);
            });

            Schema::dropIfExists('registrations');
            Schema::rename('registrations_without_cancelada', 'registrations');
            Schema::enableForeignKeyConstraints();

            return;
        }

        DB::table('registrations')->where('estado', 'Cancelada')->update(['estado' => 'Rechazado']);
        DB::statement("ALTER TABLE registrations MODIFY COLUMN estado ENUM('Registrado','En proceso','Seleccionado','Rechazado') NOT NULL DEFAULT 'Registrado'");
    }
};
