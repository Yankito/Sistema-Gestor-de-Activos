<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Vinkla\Hashids\Facades\Hashids;
class Ubicacion extends Model
{
    protected $table = 'ubicaciones';

    // Definir los campos asignables masivamente
    protected $fillable = [
        'sitio',
        'soporte_ti',
        'latitud',
        'longitud'
    ];

    // Si no estás utilizando timestamps, puedes desactivarlos
    // protected $timestamps = false;

    public function getHashedIdAttribute(){
        return Hashids::encode($this->id);
    }
}
?>
