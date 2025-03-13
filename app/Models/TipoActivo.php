<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;


class TipoActivo extends Model
{

    protected $table = 'tipo_activo';

    protected $fillable = [
        'nombre',
    ];

    public function getHashedIdAttribute(){
        return Hashids::encode($this->id);
    }

    // Relación muchos a muchos con Persona a través de la tabla asignaciones
    public function caracteristicasAdicionales()
    {
        return $this->hasMany(CaracteristicaAdicional::class, 'tipo_activo_id', 'id');
    }
}
