<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TablaDatosController extends Controller
{
    public function index()
    {
        $datos = DB::table('personas')
            ->leftJoin('activos', 'personas.id', '=', 'activos.usuario_de_activo')
            ->leftJoin('personas as usuario', 'activos.usuario_de_activo', '=', 'usuario.id')
            ->leftJoin('personas as responsable', 'activos.responsable_de_activo', '=', 'responsable.id')
            ->leftJoin('ubicaciones',DB::raw('COALESCE(activos.ubicacion, personas.ubicacion)'), '=', 'ubicaciones.id')
            ->select(
                'personas.*',
                'activos.*',
                'usuario.rut as rut_usuario',
                'responsable.rut as rut_responsable',
                'ubicaciones.sitio',
                'ubicaciones.soporteTI'
            )
            ->get();

        return view('tablaDatos',compact('datos'));
    }
}
