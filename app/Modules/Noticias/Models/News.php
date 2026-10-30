<?php

namespace App\Modules\Noticias\Models;

use App\Modules\Centros\Models\Trainingcenter;
use App\Modules\Usuarios\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;
    protected $fillable = [
        'trainingcenter_id',
        'admin_id',
        'titulo',
        'descripcion',
        'imagen',
        'estado',
        'categoria',
        'publicada',
    ];
    public function admin(){
        return $this->belongsTo(Admin::class);
    }
    public function trainingcenter(){
        return $this->belongsTo(Trainingcenter::class);
    }
}
