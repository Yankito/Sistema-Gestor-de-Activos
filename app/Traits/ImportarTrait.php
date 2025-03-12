<?php

namespace App\Traits;

use Illuminate\Support\Facades\Auth;
use App\Models\Registro;

trait ImportarTrait
{
    /**
     * Elimina tildes y convierte a mayúsculas.
     */
    protected function eliminarTildesYMayusculas($cadena)
    {
        $cadena = strtoupper($cadena);
        $buscar = ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'];
        $reemplazar = ['A', 'E', 'I', 'O', 'U', 'N'];
        return str_replace($buscar, $reemplazar, $cadena);
    }

    /**
     * Verifica si el usuario es administrador.
     */
    protected function esAdministrador()
    {
        return Auth::user()->es_administrador;
    }

    /**
     * Redirige si el usuario no es administrador.
     */
    protected function redirigirSiNoEsAdmin()
    {
        if (!$this->esAdministrador()) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        }
        return null;
    }

    /**
     * Crea un registro en la tabla Registro.
     */
    protected function crearRegistro($tipoCambio)
    {
        $registro = new Registro();
        $registro->activo = null;
        $registro->persona = null;
        $registro->tipo_cambio = $tipoCambio;
        $registro->encargado_cambio = Auth::user()->id;
        $registro->save();
    }
}