<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('results', function (Blueprint $table) {
            if (! Schema::hasColumn('results', 'codigo')) {
                $table->string('codigo')->nullable()->after('competition_id');
            }
            if (! Schema::hasColumn('results', 'nombre')) {
                $table->string('nombre')->nullable()->after('codigo');
            }
            if (! Schema::hasColumn('results', 'descripcion')) {
                $table->text('descripcion')->nullable()->after('nombre');
            }
        });

        if (! Schema::hasTable('grades')) {
            Schema::create('grades', function (Blueprint $table) {
                $table->id();
                $table->foreignId('result_id')->constrained('results')->cascadeOnDelete();
                $table->foreignId('apprentice_id')->constrained('apprentices')->cascadeOnDelete();
                $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
                $table->enum('estado_resultado', ['Aprobado', 'Reprobado', 'Pendiente'])->default('Pendiente');
                $table->text('comentarios')->nullable();
                $table->timestamps();
                $table->unique(['result_id', 'apprentice_id']);
            });
        }

        if (Schema::hasColumn('results', 'apprentice_id')) {
            $existentes = DB::table('results')->whereNotNull('apprentice_id')->get();

            foreach ($existentes as $fila) {
                DB::table('results')->where('id', $fila->id)->update([
                    'nombre' => $fila->nombre ?: 'Resultado de aprendizaje',
                    'codigo' => $fila->codigo ?: ('RA-'.$fila->id),
                ]);

                DB::table('grades')->insert([
                    'result_id' => $fila->id,
                    'apprentice_id' => $fila->apprentice_id,
                    'estado_resultado' => $fila->estado_resultado ?: 'Pendiente',
                    'comentarios' => $fila->comentarios,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }

            if (Schema::getConnection()->getDriverName() === 'sqlite') {
                Schema::disableForeignKeyConstraints();

                Schema::create('results_rebuilt', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('competition_id')->constrained('competitions')->cascadeOnDelete();
                    $table->string('codigo')->nullable();
                    $table->string('nombre')->nullable();
                    $table->text('descripcion')->nullable();
                    $table->timestamps();
                });

                DB::table('results')->orderBy('id')->get()->each(function ($resultado) {
                    DB::table('results_rebuilt')->insert([
                        'id' => $resultado->id,
                        'competition_id' => $resultado->competition_id,
                        'codigo' => $resultado->codigo,
                        'nombre' => $resultado->nombre,
                        'descripcion' => $resultado->descripcion,
                        'created_at' => $resultado->created_at,
                        'updated_at' => $resultado->updated_at,
                    ]);
                });

                Schema::dropIfExists('results');
                Schema::rename('results_rebuilt', 'results');
                Schema::enableForeignKeyConstraints();

                return;
            }

            Schema::table('results', function (Blueprint $table) {
                if (Schema::hasColumn('results', 'apprentice_id')) {
                    $table->dropForeign(['apprentice_id']);
                    $table->dropColumn(['apprentice_id']);
                }
                if (Schema::hasColumn('results', 'estado_resultado')) {
                    $table->dropColumn(['estado_resultado']);
                }
                if (Schema::hasColumn('results', 'comentarios')) {
                    $table->dropColumn(['comentarios']);
                }
            });
        }
    }

    public function down(): void
    {
        Schema::table('results', function (Blueprint $table) {
            $table->unsignedBigInteger('apprentice_id')->nullable();
            $table->enum('estado_resultado', ['Aprobado', 'Reprobado', 'Pendiente'])->default('Pendiente');
            $table->text('comentarios')->nullable();
        });

        $calificaciones = DB::table('grades')->get();
        foreach ($calificaciones as $calificacion) {
            DB::table('results')->where('id', $calificacion->result_id)->update([
                'apprentice_id' => $calificacion->apprentice_id,
                'estado_resultado' => $calificacion->estado_resultado,
                'comentarios' => $calificacion->comentarios,
            ]);
        }

        Schema::table('results', function (Blueprint $table) {
            if (Schema::getConnection()->getDriverName() !== 'sqlite') {
                $table->foreign('apprentice_id')->references('id')->on('apprentices')->onDelete('cascade');
            }
            $table->dropColumn(['codigo', 'nombre', 'descripcion']);
        });

        Schema::dropIfExists('grades');
    }
};
