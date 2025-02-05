<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Activo extends Model
{
    // Definir el nombre de la tabla si no sigue la convención
    protected $table = 'activos';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'nro_serie',
        'marca',
        'modelo',
        'tipo_de_activo',
        'estado',
        'usuario_de_activo',
        'responsable_de_activo',
        'precio',
        'ubicacion',
        'justificacion_doble_activo'
    ];

    // Si no utilizas timestamps (created_at y updated_at), puedes desactivarlos
    public $timestamps = true;

    // Relación con la tabla Persona
    public function usuarioDeActivo(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'usuario_de_activo');
    }

    public function responsableDeActivo(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'responsable_de_activo');
    }

    public function ubicacion(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion');
    }

}
