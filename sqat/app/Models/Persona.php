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
        'nombreUsuario',
        'nombres',
        'primerApellido',
        'segundoApellido',
        'supervisor',
        'empresa',
        'estadoEmpleado',
        'centroCosto',
        'denominacion',
        'tituloPuesto',
        'fechaInicio',
        'usuarioTI',
        'ubicacion',
    ];

    // Activar timestamps si se están usando las columnas created_at y updated_at
    public $timestamps = true;

    // Relación con la tabla Ubicacion (cada Persona pertenece a una Ubicacion)
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion', 'id');
    }

    // Método para obtener el nombre completo de una persona
    public function getNombreCompletoAttribute()
    {
        return "{$this->nombres} {$this->primerApellido} {$this->segundoApellido}";
    }

    // Scope para buscar personas activas
    public function scopeActivas($query)
    {
        return $query->where('estadoEmpleado', true);
    }
}
