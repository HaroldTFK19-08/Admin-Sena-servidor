<?php

namespace App\Modules\Resultados\Models;

use App\Modules\Competencias\Models\Competition;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;

    protected $fillable = [
        'competition_id',
        'codigo',
        'nombre',
        'descripcion',
    ];

    public function competition()
    {
        return $this->belongsTo(Competition::class);
    }

    public function competence()
    {
        return $this->competition();
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    public function puedeEliminarse(): bool
    {
        return ! $this->grades()->exists();
    }
}
