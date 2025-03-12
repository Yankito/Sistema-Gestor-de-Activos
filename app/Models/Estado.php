<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Estado extends Model
{
    // Definir el nombre de la tabla si no sigue la convención
    protected $table = 'estados';

    // Definir los campos que pueden ser asignados masivamente
    protected $fillable = [
        'nombre_estado',
        'descripcion'
    ];

}
?>
