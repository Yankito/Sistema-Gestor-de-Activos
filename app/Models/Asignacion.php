<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Asignacion extends Model
{
    // Nombre de la tabla (opcional, si no sigue la convención de nombres de Laravel)
    protected $table = 'asignaciones';

    // Campos asignables masivamente
    protected $fillable = [
        'id_persona',
        'id_activo',
    ];

    // Desactivar timestamps si no los necesitas
    public $timestamps = false;

    // Relación con el modelo Persona
    public function persona()
    {
        return $this->belongsTo(Persona::class, 'id_persona');
    }

    // Relación con el modelo Activo
    public function activo()
    {
        return $this->belongsTo(Activo::class, 'id_activo');
    }
}
?>
