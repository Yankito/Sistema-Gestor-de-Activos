<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Usuario extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'usuarios';

    protected $fillable = [
        'correo',
        'nombres',
        'primer_apellido',
        'segundo_apellido',
        'contrasena',
        'es_administrador',
    ];

    protected $hidden = [
        'contrasena',
    ];

    // Especificar el campo que Laravel debe usar como contraseña
    public function getAuthPassword()
    {
        return $this->contrasena;
    }
}
