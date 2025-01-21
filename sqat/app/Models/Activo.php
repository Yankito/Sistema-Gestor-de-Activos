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
        'tipoActivo',
        'estado',
        'usuarioDeActivo',
        'responsableDeActivo',
        'docking',
        'parlanteJabra',
        'discoDuroExt',
        'impresoraExclusiva',
        'monitor',
        'mouse',
        'teclado',
        'justificacionDobleActivo',
        'precio',
    ];

    // Si el campo 'nroSerie' no es auto incrementable, debes desactivar el incremento automático
    public $incrementing = false;

    // Si no utilizas timestamps (created_at y updated_at), puedes desactivarlos
    public $timestamps = true;

    // Relación con la tabla Persona
    public function usuarioDeActivo()
    {
        return $this->belongsTo(Persona::class, 'usuarioDeActivo', 'rut');
    }

    public function responsableDeActivo()
    {
        return $this->belongsTo(Persona::class, 'responsableDeActivo', 'rut');
    }
}
