<?php

namespace App\Modules\Programas\Models;

use App\Modules\Areas\Models\Area;
use App\Modules\Ofertas\Models\Offer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    protected $fillable = [
        'area_id',
        'nombre',
        'codigo_programa',
        'nivel_programa',
    ];
    public function area(){
        return $this->belongsTo(Area::class);
    }
    public function offerts(){
        return $this->hasMany(Offer::class);
    }
}
