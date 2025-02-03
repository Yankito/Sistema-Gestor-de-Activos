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
        'tipoCambio',
        'encargadoCambio',
    ];

    // Si no utilizas timestamps (created_at y updated_at), puedes desactivarlos
    public $timestamps = true;

    // Relación con la tabla Persona
    public function usuarioDeActivo()
    {
        return $this->belongsTo(Persona::class, 'persona', 'id');
    }

    public function responsableDeActivo()
    {
        return $this->belongsTo(Activo::class, 'activo', 'id');
    }

    public function ubicacion()
    {
        return $this->belongsTo(Usuario::class, 'encargadoCambio', 'id');
    }

}

