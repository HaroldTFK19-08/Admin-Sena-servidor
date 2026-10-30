<?php

namespace App\Modules\Equipos\Models;

use App\Modules\Ambientes\Models\Environment;
use App\Modules\Asignaciones\Models\Assignment;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Equipment extends Model
{
    use HasFactory;
    protected $fillable = [
        'environment_id',
        'name',
        'brand',
        'equipment_type',
    ];
    protected $table = 'equipments';
    public function environment(){
        return $this->belongsTo(Environment::class);
    }
    public function assignments(){
        return $this->hasMany(Assignment::class);
    }
}
