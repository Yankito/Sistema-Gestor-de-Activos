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
        $tipoDeActivos = $this->obtenerTiposDeActivos();
        // Pasar el usuario a la vista
        return view('dashboard', compact('cantidadActivos','cantidadPersonas','cantidadUbicaciones','activos','cantidadPorUbicacion','ubicaciones', 'tipoDeActivos'));
    }

    public function calcularActivosPorUbicacion(){
        $ubicaciones = Ubicacion::all();
        $cantidadPorUbicacion = [];
        foreach ($ubicaciones as $ubicacion) {
            $cantidadPorUbicacion[$ubicacion->sitio] = Activo::where('ubicacion', $ubicacion->id)->count();
        }
        return $cantidadPorUbicacion;
    }

    public function obtenerTiposDeActivos(){
        $activos = Activo::all();
        $tipos = [];
        foreach ($activos as $activo) {
            $tipos[$activo->tipoActivo] = Activo::where('tipoActivo', $activo->tipoActivo)->count();
        }
        return $tipos;
    }
}
