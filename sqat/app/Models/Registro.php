<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    protected $table = 'registros';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'persona',
        'activo',
        'tipo_cambio',
        'encargado_cambio',
    ];

    // Si no utilizas timestamps (created_at y updated_at), puedes desactivarlos
    public $timestamps = true;

    // Relación con la tabla Persona
    public function personaRelation()
    {
        return $this->belongsTo(Persona::class, 'persona', 'id');
    }

    public function activoRelation()
    {
        return $this->belongsTo(Activo::class, 'activo', 'id');
    }

    public function encargadoCambio()
    {
        return $this->belongsTo(Usuario::class, 'encargado_cambio', 'id');
    }

}

