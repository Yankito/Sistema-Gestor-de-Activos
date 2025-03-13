<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Persona extends Model
{
    // Nombre de la tabla asociada en la base de datos
    protected $table = 'personas';
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'user',
        'rut',
        'nombre_completo',
        'nombre_empresa',
        'estado_empleado',
        'fecha_ing',
        'fecha_ter',
        'cargo',
        'ubicacion',
        'correo'
    ];

    // Activar timestamps si se están usando las columnas created_at y updated_at
    public $timestamps = true;

    // Relación con la tabla Ubicacion (cada Persona pertenece a una Ubicacion)
    public function ubicacionRelation()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion', 'id');
    }
    // Scope para buscar personas activas
    public function scopeActivas($query)
    {
        return $query->where('estado_empleado', true);
    }

    // Relación muchos a muchos con Activo a través de la tabla asignaciones
    public function activos(): BelongsToMany
    {
        return $this->belongsToMany(Activo::class, 'asignaciones', 'id_persona', 'id_activo');
    }
}
