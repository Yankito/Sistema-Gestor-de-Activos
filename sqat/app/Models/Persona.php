<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Persona extends Model
{
    // Definir el nombre de la tabla si no sigue la convención
    protected $table = 'personas';

    // Definir los campos que pueden ser asignados masivamente
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

    // Si el campo 'rut' no es auto incrementable, desactivar el incremento automático
    public $incrementing = false;

    // Si no utilizas timestamps (created_at y updated_at), puedes desactivarlos
    public $timestamps = true;

    // Relación con la tabla Ubicacion (cada Persona tiene una Ubicación)
    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion', 'id');
    }
}
