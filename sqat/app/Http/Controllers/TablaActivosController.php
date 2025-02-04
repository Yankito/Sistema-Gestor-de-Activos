<?php

namespace App\Http\Controllers;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;

class TablaActivosController extends Controller
{
    public function index()
    {
        $datos = DB::table('activos')
            ->leftJoin('personas as usuario', 'activos.usuario_de_activo', '=', 'usuario.id')
            ->leftJoin('personas as responsable', 'activos.responsable_de_activo', '=', 'responsable.id')
            ->leftJoin('ubicaciones', DB::raw('COALESCE(usuario.ubicacion, activos.ubicacion)'), '=', 'ubicaciones.id')
            ->select(
                'activos.*',
                'usuario.rut as rut_usuario',
                'responsable.rut as rut_responsable',
                'ubicaciones.sitio',
                'ubicaciones.soporte_ti'
            )
            ->get();

        return view('activos.tablaActivos', compact('datos'));
    }
}
