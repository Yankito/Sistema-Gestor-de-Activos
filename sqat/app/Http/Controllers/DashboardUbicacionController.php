<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Estado;


class DashboardUbicacionController extends Controller
{
    public function index(){
        $ubicacion = Ubicacion::where("id","=","1")->first();
        $cantidadActivos = Activo::where('ubicacion', $ubicacion->id)->count();
        $tiposDeActivo = $this->obtenerTiposdeActivo($ubicacion->id);
        $cantidadPorEstados = $this->calcularActivosPorEstados($ubicacion->id);
        $ubicaciones = Ubicacion::all();
        return view('dashboards.dashboardUbicacion', compact('cantidadActivos', 'ubicacion', 'tiposDeActivo', 'cantidadPorEstados', 'cantidadPorEstados', 'ubicaciones'));
    }

    public function obtenerTiposdeActivo($ubicacion){
        $activos = Activo::where('ubicacion', $ubicacion)->get();
        $tiposDeActivo = [];
        foreach ($activos as $activo) {
            $activo->tipo_de_activo = strtoupper($activo->tipo_de_activo);
            $tiposDeActivo[$activo->tipo_de_activo] = Activo::where('tipo_de_activo', $activo->tipo_de_activo)
                                                          ->where('ubicacion', $ubicacion)
                                                          ->count();
        }
        return $tiposDeActivo;
    }

    public function calcularActivosPorEstados($ubicacion){
        $estados = [];
        $estadosDisponibles = Estado::all();
        foreach ($estadosDisponibles as $estado) {
            $estados[$estado->nombre_estado] = [
                'cantidad' => Activo::where('estado', $estado->id)
                                    ->where('ubicacion', $ubicacion)
                                    ->count(),
                'descripcion' => $estado->descripcion
            ];
        }
        return $estados;
    }

    public function actualizarUbicacion(Request $request){
        $ubicacion = Ubicacion::where('id', $request->ubicacion_id)->first();
        $cantidadActivos = Activo::where('ubicacion', $ubicacion->id)->count();
        $tiposDeActivo = $this->obtenerTiposdeActivo($ubicacion->id);
        $cantidadPorEstados = $this->calcularActivosPorEstados($ubicacion->id);
        $ubicaciones = Ubicacion::all();
        return view('dashboards.dashboardUbicacion', compact('cantidadActivos', 'ubicacion', 'tiposDeActivo', 'cantidadPorEstados', 'cantidadPorEstados', 'ubicaciones'));
    }
}
