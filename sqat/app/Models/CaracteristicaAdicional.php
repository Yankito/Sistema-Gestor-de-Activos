<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class CaracteristicaAdicional extends Model
{
    protected $table = 'caracteristicas_adicionales';

    // Campos asignables masivamente
    protected $fillable = [
        'tipo_activo_id',
        'nombre_caracteristica',
    ];

    // Desactivar timestamps si no los necesitas
    public $timestamps = false;

    // Relación con el modelo Persona
    public function tipoActivoId()
    {
        return $this->belongsTo(TipoActivo::class, 'tipo_activo_id');
    }
    public function tipoActivo()
    {
        return $this->belongsTo(TipoActivo::class, 'tipo_activo_id', 'id');
    }
}
