<?php

namespace App\Modules\Ofertas\Models;

use App\Modules\Inscripciones\Models\Registration;
use App\Modules\Programas\Models\Program;
use App\Modules\Usuarios\Models\Admin;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    use HasFactory;

    protected $casts = [
        'fecha_lanzamiento' => 'date',
        'fecha_convocatoria' => 'date',
        'fecha_fin_convocatoria' => 'date',
        'fecha_primera_prueba' => 'date',
        'fecha_segunda_prueba' => 'date',
        'fecha_seleccionados' => 'date',
        'publicada' => 'boolean'
    ];
    protected $fillable = [
        'admin_id',
        'program_id',
        'fecha_lanzamiento',
        'capacidad',
        'fecha_convocatoria',
        'fecha_fin_convocatoria',
        'fecha_primera_prueba',
        'fecha_segunda_prueba',
        'fecha_seleccionados',
        'imagen',
        'public_id',
        'publicada'
    ];
    public function inscriptions()
    {
        return $this->hasMany(Registration::class);
    }

    public function inscripcionesActivas()
    {
        return $this->inscriptions()->whereNotIn('estado', ['Cancelada']);
    }

    public function inscripcionesActivasCount(): int
    {
        if (array_key_exists('inscripciones_activas_count', $this->attributes)) {
            return (int) $this->attributes['inscripciones_activas_count'];
        }

        return $this->inscripcionesActivas()->count();
    }

    public function estaAbierta(): bool
    {
        if (! $this->publicada) {
            return false;
        }

        if ($this->fecha_fin_convocatoria && $this->fecha_fin_convocatoria->copy()->endOfDay()->isPast()) {
            return false;
        }

        if ($this->capacidad) {
            return $this->inscripcionesActivasCount() < $this->capacidad;
        }

        return true;
    }
    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }
}
