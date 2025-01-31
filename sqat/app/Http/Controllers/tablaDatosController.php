<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TablaDatosController extends Controller
{
    public function index()
    {
        $datos = DB::table('personas')
            ->leftJoin('activos', 'personas.rut', '=', 'activos.usuarioDeActivo')
            ->leftJoin('ubicaciones',DB::raw('COALESCE(activos.ubicacion, personas.ubicacion)'), '=', 'ubicaciones.id')
            ->select(
                'personas.*',
                'activos.*',
                'ubicaciones.sitio',
                'ubicaciones.soporteTI'
            )
            ->get();

        return view('tablaDatos',compact('datos'));
    }
}
