<?php

namespace App\Modules\Asignaciones\Models;

use App\Modules\Equipos\Models\Equipment;
use App\Modules\Usuarios\Models\Apprentice;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha_asignacion' => 'datetime',
        'fecha_devolucion' => 'datetime',
    ];

    protected $fillable = [
        'equipment_id',
        'apprentice_id',
        'fecha_asignacion',
        'fecha_devolucion',
        'Estado',
    ];

    public function equipment()
    {
        return $this->belongsTo(Equipment::class);
    }

    public function apprentice()
    {
        return $this->belongsTo(Apprentice::class);
    }
}
