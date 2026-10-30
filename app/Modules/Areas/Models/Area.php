<?php

namespace App\Modules\Areas\Models;

use App\Modules\Centros\Models\Trainingcenter;
use App\Modules\Programas\Models\Program;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainingcenter_id',
        'nombre',
    ];

    public function trainingcenter()
    {
        return $this->belongsTo(Trainingcenter::class);
    }

    public function programs()
    {
        return $this->hasMany(Program::class);
    }
}
