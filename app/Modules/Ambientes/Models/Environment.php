<?php

namespace App\Modules\Ambientes\Models;

use App\Modules\Centros\Models\Trainingcenter;
use App\Modules\Equipos\Models\Equipment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Environment extends Model
{
    use HasFactory;

    protected $fillable = [
        'trainingcenter_id',
        'nombre',
        'capacidad',
        'tipo_ambiente',
    ];

    public function trainingcenter()
    {
        return $this->belongsTo(Trainingcenter::class);
    }

    public function equipments()
    {
        return $this->hasMany(Equipment::class);
    }
}
