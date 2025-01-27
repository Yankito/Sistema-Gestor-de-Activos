<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activo;
use App\Models\Ubicacion;

class DashboardActivosController extends Controller
{
    public function index()
    {
        $activos = Activo::all();
        $cantidadPorUbicacion = $this->calcularActivosPorUbicacion();
        $ubicaciones = Ubicacion::all();
        return view("dashboardActivos", compact('activos','cantidadPorUbicacion','ubicaciones'));
    }

    public function calcularActivosPorUbicacion(){
        $ubicaciones = Ubicacion::all();
        $cantidadPorUbicacion = [];
        foreach ($ubicaciones as $ubicacion) {
            $cantidadPorUbicacion[$ubicacion->sitio] = Activo::where('ubicacion', $ubicacion->id)->count();
        }
        return $cantidadPorUbicacion;
    }
}
