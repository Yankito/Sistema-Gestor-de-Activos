<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class TipoActivo extends Model
{

    protected $table = 'tipo_activo';

    protected $fillable = [
        'nombre',
    ];

    // Relación muchos a muchos con Persona a través de la tabla asignaciones
    public function caracteristicasAdicionales()
    {
        return $this->hasMany(CaracteristicaAdicional::class, 'tipo_activo_id', 'id');
    }
}
