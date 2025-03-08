<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
        'responsable_de_activo',
        'precio',
        'ubicacion',
        'justificacion_doble_activo'
    ];

    // Si no utilizas timestamps (created_at y updated_at), puedes desactivarlos
    public $timestamps = true;

    // Relación muchos a muchos con Persona a través de la tabla asignaciones
    public function usuarioDeActivo(): BelongsToMany
    {
        return $this->belongsToMany(Persona::class, 'asignaciones', 'id_activo', 'id_persona');
    }

    // Relación con la tabla Persona para el responsable
    public function responsableDeActivo(): BelongsTo
    {
        return $this->belongsTo(Persona::class, 'responsable_de_activo');
    }

    // Otras relaciones existentes
    public function ubicacionRelation(): BelongsTo
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion');
    }

    public function estadoRelation(): BelongsTo
    {
        return $this->belongsTo(Estado::class, 'estado');
    }

    public function tipoDeActivo(): BelongsTo
    {
        return $this->belongsTo(TipoActivo::class, 'tipo_de_activo');
    }

    public function valoresAdicionales(): HasMany
    {
        return $this->hasMany(ValorAdicional::class, 'id_activo', 'id');
    }
}
