<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;


class DashboardController extends Controller
{
    public function index()
    {
        $cantidadActivos = Activo::count();
        $cantidadPersonas = Persona::count();
        $cantidadUbicaciones = Ubicacion::count();

        $activos = Activo::all();
        $cantidadPorUbicacion = $this->calcularActivosPorUbicacion();
        $ubicaciones = Ubicacion::all();
        $tiposDeActivo = $this->obtenerTiposdeActivo();
        $cantidadPorEstados = $this->calcularActivosPorEstados();
        // Pasar el usuario a la vista
        return view('dashboard', compact('cantidadActivos',
        'cantidadPersonas','cantidadUbicaciones','activos',
        'cantidadPorUbicacion','ubicaciones', 'tiposDeActivo', 'cantidadPorEstados'));
    }

    public function calcularActivosPorUbicacion(){
        $ubicaciones = Ubicacion::all();
        $cantidadPorUbicacion = [];
        foreach ($ubicaciones as $ubicacion) {
            $cantidadPorUbicacion[$ubicacion->sitio] = Activo::where('ubicacion', $ubicacion->id)->count();
        }
        return $cantidadPorUbicacion;
    }

    public function obtenerTiposdeActivo(){
        $activos = Activo::all();
        $tiposDeActivo = [];
        foreach ($activos as $activo) {
            $activo->tipo_de_activo = strtoupper($activo->tipo_de_activo);
            $tiposDeActivo[$activo->tipo_de_activo] = Activo::where('tipo_de_activo', $activo->tipo_de_activo)->count();
        }
        return $tiposDeActivo;
    }

    public function calcularActivosPorEstados(){
        $estados = [];
        $estadosDisponibles = ['ASIGNADO', 'DISPONIBLE', 'ROBADO', 'PARA BAJA', 'DONADO'];
        foreach ($estadosDisponibles as $estado) {
            $estados[$estado] = Activo::where('estado', $estado)->count();
        }
        return $estados;
    }


}
