<?php

namespace App\Modules\Usuarios\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected static function newFactory()
    {
        return UserFactory::new();
    }

    protected $fillable = [
        'nombre_1',
        'nombre_2',
        'apellido_1',
        'apellido_2',
        'foto_perfil',
        'public_id',
        'tipo_identificacion',
        'identificacion',
        'rol',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function admin()
    {
        return $this->hasOne(Admin::class);
    }

    public function apprentice()
    {
        return $this->hasOne(Apprentice::class);
    }

    public function teacher()
    {
        return $this->hasOne(Teacher::class);
    }

    public function aspirant()
    {
        return $this->hasOne(Aspirant::class);
    }

    public function getNombreCompletoAttribute(): string
    {
        return trim(collect([
            $this->nombre_1,
            $this->nombre_2,
            $this->apellido_1,
            $this->apellido_2,
        ])->filter()->implode(' '));
    }
}
