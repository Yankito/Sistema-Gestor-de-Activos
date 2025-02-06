<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    // Nombre de la tabla asociada en la base de datos
    protected $table = 'personas';
    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'rut',
        'nombre_usuario',
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'supervisor',
        'empresa',
        'estado_empleado',
        'centro_costo',
        'denominacion',
        'titulo_puesto',
        'fecha_inicio',
        'usuario_ti',
        'ubicacion',
    ];

    // Activar timestamps si se están usando las columnas created_at y updated_at
    public $timestamps = true;

    // Relación con la tabla Ubicacion (cada Persona pertenece a una Ubicacion)
    public function ubicacionRelation()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion', 'id');
    }

    // Método para obtener el nombre completo de una persona
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->primer_apellido} {$this->segundo_apellido}";
    }

    // Scope para buscar personas activas
    public function scopeActivas($query)
    {
        return $query->where('estado_empleado', true);
    }
}
