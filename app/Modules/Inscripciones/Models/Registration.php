<?php

namespace App\Modules\Inscripciones\Models;

use App\Modules\Ofertas\Models\Offer;
use App\Modules\Usuarios\Models\Aspirant;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    use HasFactory;

    public const ESTADOS = [
        'Registrado',
        'En proceso',
        'Seleccionado',
        'Rechazado',
        'Cancelada',
    ];

    public const ESTADOS_EDITABLES = [
        'Registrado',
    ];

    public const ESTADOS_CANCELABLES = [
        'Registrado',
        'En proceso',
    ];

    protected $casts = [
        'Fecha_inscripcion' => 'datetime',
    ];

    protected $fillable = [
        'aspirant_id',
        'offer_id',
        'estado',
        'Fecha_inscripcion',
    ];

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function aspirant()
    {
        return $this->belongsTo(Aspirant::class);
    }

    public function permiteEdicion(): bool
    {
        return in_array($this->estado, self::ESTADOS_EDITABLES, true);
    }

    public function permiteCancelacion(): bool
    {
        return in_array($this->estado, self::ESTADOS_CANCELABLES, true);
    }

    public function estaCancelada(): bool
    {
        return $this->estado === 'Cancelada';
    }

    public function estaActiva(): bool
    {
        return ! $this->estaCancelada();
    }
}
