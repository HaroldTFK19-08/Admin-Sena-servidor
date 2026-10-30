<?php

namespace App\Modules\Centros\Models;
use App\Modules\Ambientes\Models\Environment;
use App\Modules\Areas\Models\Area;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Trainingcenter extends Model
{
    use HasFactory;
    protected $fillable = [
        'nombre',
        'direccion',
    ];
    public function areas(){
        return $this->hasMany(Area::class);
    }
    public function environments(){
        return $this->hasMany(Environment::class);
    }
}
