<?php

namespace App\Http\Controllers;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;

class TablaActivosController extends Controller
{
    public function index()
    {
        $datos = DB::table('activos')
            ->leftJoin('personas as usuario', 'activos.usuarioDeActivo', '=', 'usuario.id')
            ->leftJoin('personas as responsable', 'activos.responsableDeActivo', '=', 'responsable.id')
            ->leftJoin('ubicaciones', DB::raw('COALESCE(usuario.ubicacion, activos.ubicacion)'), '=', 'ubicaciones.id')
            ->select(
                'activos.*',
                'usuario.rut as rutUsuario',
                'responsable.rut as rutResponsable',
                'ubicaciones.sitio',
                'ubicaciones.soporteTI'
            )
            ->get();

        return view('tablaActivos', compact('datos'));
    }
}
