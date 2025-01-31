<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Activo extends Model
{
    // Definir el nombre de la tabla si no sigue la convención
    protected $table = 'activos';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'nroSerie',
        'marca',
        'modelo',
        'estado',
        'usuarioDeActivo',
        'responsableDeActivo',
        'precio',
        'ubicacion',
        'justificacionDobleActivo'
    ];

    // Si no utilizas timestamps (created_at y updated_at), puedes desactivarlos
    public $timestamps = true;

    // Relación con la tabla Persona
    public function usuarioDeActivo()
    {
        return $this->belongsTo(Persona::class, 'usuarioDeActivo', 'id');
    }

    public function responsableDeActivo()
    {
        return $this->belongsTo(Persona::class, 'responsableDeActivo', 'id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Ubicacion::class, 'ubicacion', 'id');
    }

}
