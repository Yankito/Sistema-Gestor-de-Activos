<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoActivo extends Model
{

    protected $table = 'tipo_activo';

    protected $fillable = [
        'nombre',
    ];
}
?>