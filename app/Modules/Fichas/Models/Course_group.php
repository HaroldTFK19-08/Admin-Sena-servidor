<?php

namespace App\Modules\Fichas\Models;

use App\Modules\Programas\Models\Program;
use App\Modules\Usuarios\Models\Apprentice;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course_group extends Model
{
    use HasFactory;
    protected $fillable = [
        'program_id',
        'teacher_id',
        'codigo',
        'capacidad_aprendices',
    ];
    public function program(){
        return $this->belongsTo(Program::class);
    }
    public function apprentices(){
        return $this->hasMany(Apprentice::class);
    }
    public function teachers(){
        return $this->belongsToMany(Teacher::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
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
