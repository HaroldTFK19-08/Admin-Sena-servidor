<?php

namespace App\Modules\Usuarios\Models;

use App\Modules\Inscripciones\Models\Registration;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aspirant extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'eps',
        'certificado_eps',
        'certificado_bachiller',
        'certificado_icfes',
    ];
    public function user(){
        return $this->belongsTo(User::class);
    }
    public function inscriptions(){
        return $this->hasMany(Registration::class);
    }
}
