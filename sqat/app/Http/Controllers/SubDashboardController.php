<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;

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
            $activo->tipoDeActivo = strtoupper($activo->tipoDeActivo);
            $tiposDeActivo[$activo->tipoDeActivo] = Activo::where('tipoDeActivo', $activo->tipoDeActivo)
                                                          ->where('ubicacion', $ubicacion)
                                                          ->count();
        }
        return $tiposDeActivo;
    }

    public function calcularActivosPorEstados($ubicacion){
        $estados = [];
        $estadosDisponibles = ['ASIGNADO', 'DISPONIBLE', 'ROBADO', 'PARA BAJA', 'DONADO'];
        foreach ($estadosDisponibles as $estado) {
            $estados[$estado] = Activo::where('estado', $estado)
                                        ->where('ubicacion', $ubicacion)
                                        ->count();
        }
        return $estados;
    }
}
