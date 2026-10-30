<?php

namespace App\Modules\Usuarios\Models;

use App\Modules\Areas\Models\Area;
use App\Modules\Competencias\Models\Competition;
use App\Modules\Fichas\Models\Course_group;
use App\Modules\Resultados\Models\Grade;
use App\Modules\Resultados\Models\Result;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    protected $fillable = [
        'user_id',
        'area_id',
        'especialidad',
        'tipo_instructor',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function competencies()
    {
        return $this->hasMany(Competition::class);
    }

    public function competitions()
    {
        return $this->belongsToMany(
            Competition::class,
            'competition_teacher',
            'teacher_id',
            'competency_id'
        );
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function results()
    {
        return $this->hasManyThrough(Result::class, Competition::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function coursegroups()
    {
        return $this->belongsToMany(Course_group::class);
    }
}
