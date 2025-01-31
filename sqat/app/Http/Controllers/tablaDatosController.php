<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TablaDatosController extends Controller
{
    public function index()
    {
        $datos = DB::table('personas')
            ->leftJoin('activos', 'personas.id', '=', 'activos.usuarioDeActivo')
            ->leftJoin('personas as usuario', 'activos.usuarioDeActivo', '=', 'usuario.id')
            ->leftJoin('personas as responsable', 'activos.responsableDeActivo', '=', 'responsable.id')
            ->leftJoin('ubicaciones',DB::raw('COALESCE(activos.ubicacion, personas.ubicacion)'), '=', 'ubicaciones.id')
            ->select(
                'personas.*',
                'activos.*',
                'usuario.rut as rutUsuario',
                'responsable.rut as rutResponsable',
                'ubicaciones.sitio',
                'ubicaciones.soporteTI'
            )
            ->get();

        return view('tablaDatos',compact('datos'));
    }
}
