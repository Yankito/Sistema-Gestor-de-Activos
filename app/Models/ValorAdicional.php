<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ValorAdicional extends Model
{
    protected $table = 'valores_adicionales';

    // Campos asignables masivamente
    protected $fillable = [
        'id_activo',
        'id_caracteristica',
        'valor'
    ];

    // Desactivar timestamps si no los necesitas
    public $timestamps = false;

    // Relación con el modelo Activo
    public function idActivo()
    {
        return $this->belongsTo(Activo::class, 'id_activo');
    }

    // Relación con el modelo Activo
    public function idCaracteristica()
    {
        return $this->belongsTo(CaracteristicaAdicional::class, 'id_caracteristica');
    }

}
?>
