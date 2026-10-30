<?php

namespace App\Modules\Resultados\Models;

use App\Modules\Usuarios\Models\Apprentice;
use App\Modules\Usuarios\Models\Teacher;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grade extends Model
{
    use HasFactory;

    public const ESTADOS = [
        'Aprobado',
        'Reprobado',
        'Pendiente',
    ];

    protected $fillable = [
        'result_id',
        'apprentice_id',
        'teacher_id',
        'estado_resultado',
        'comentarios',
    ];

    public function result()
    {
        return $this->belongsTo(Result::class);
    }

    public function apprentice()
    {
        return $this->belongsTo(Apprentice::class);
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }
}
