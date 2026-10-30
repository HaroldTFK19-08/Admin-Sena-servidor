<?php

namespace App\Modules\Competencias\Models;

use App\Modules\Resultados\Models\Result;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Competition extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    protected $fillable = [
        'teacher_id',
        'codigo',
        'nombre',
        'descripcion',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function results()
    {
        return $this->hasMany(Result::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(
            Teacher::class,
            'competition_teacher',
            'competency_id',
            'teacher_id'
        );
    }

    public function scopeAsignadasAlInstructor($query, Teacher $teacher)
    {
        return $query->where(function ($consulta) use ($teacher) {
            $consulta->where('teacher_id', $teacher->id)
                ->orWhereHas('teachers', function ($relacion) use ($teacher) {
                    $relacion->where('teachers.id', $teacher->id);
                });
        });
    }

    public function estaAsignadaAlInstructor(Teacher $teacher): bool
    {
        if ((int) $this->teacher_id === (int) $teacher->id) {
            return true;
        }

        return $this->teachers()->where('teachers.id', $teacher->id)->exists();
    }
}
