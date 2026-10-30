<?php

namespace App\Modules\Usuarios\Models;

use App\Modules\Asignaciones\Models\Assignment;
use App\Modules\Fichas\Models\Course_group;
use App\Modules\Resultados\Models\Grade;
use App\Modules\Resultados\Models\Result;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Apprentice extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha_inicio' => 'date',
        'fecha_fin' => 'date',
    ];

    protected $fillable = [
        'user_id',
        'course_group_id',
        'estado',
        'fecha_inicio',
        'fecha_fin',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function coursegroup()
    {
        return $this->belongsTo(Course_group::class, 'course_group_id');
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function results()
    {
        return $this->hasManyThrough(
            Result::class,
            Grade::class,
            'apprentice_id',
            'result_id'
        );
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}
