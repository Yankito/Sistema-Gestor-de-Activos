<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Estado;


class SubDashboardController extends Controller
{
    public function index(){
        $ubicacion = Ubicacion::where("id","=","1")->first();
        $cantidadActivos = Activo::where('ubicacion', $ubicacion->id)->count();
        $tiposDeActivo = $this->obtenerTiposdeActivo($ubicacion->id);
        $cantidadPorEstados = $this->calcularActivosPorEstados($ubicacion->id);
        return view('subdashboard', compact('cantidadActivos', 'ubicacion', 'tiposDeActivo', 'cantidadPorEstados', 'cantidadPorEstados'));
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
            $estados[$estado->nombre_estado] = Activo::where('estado', $estado->id)
                                        ->where('ubicacion', $ubicacion)
                                        ->count();
        }
        return $estados;
    }
}
