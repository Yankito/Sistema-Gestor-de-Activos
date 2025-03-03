<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Estado;

class DashboardFiltrosController extends Controller
{
    public function index()
    {
        $tipoDeActivo = request()->query('tipo');
        $cantidadPersonas = Persona::count();
        $cantidadUbicaciones = Ubicacion::count();

        $activos = Activo::all();
        $cantidadPorUbicacion = $this->calcularActivosPorUbicacion($tipoDeActivo);
        $ubicaciones = Ubicacion::all();
        $tiposDeActivo = $this->obtenerTiposdeActivo();
        $cantidadPorEstados = $this->calcularActivosPorEstados($tipoDeActivo);
        // Pasar el usuario a la vista
        return view('dashboards.dashboardFiltros', compact(
        'cantidadPersonas','cantidadUbicaciones','activos',
        'cantidadPorUbicacion','ubicaciones', 'tiposDeActivo', 'cantidadPorEstados','tipoDeActivo'));
    }

    public function calcularActivosPorUbicacion($tipoDeActivo){
        $ubicaciones = Ubicacion::all();
        $cantidadPorUbicacion = [];
        foreach ($ubicaciones as $ubicacion) {
            $query = Activo::where('ubicacion', $ubicacion->id);
            if ($tipoDeActivo) {
                $query->where('tipo_de_activo', $tipoDeActivo);
            }
            $cantidadPorUbicacion[$ubicacion->id] = $query->count();
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

    public function calcularActivosPorEstados($tipoDeActivo){
        $estados = [];
        $estadosDisponibles = Estado::all();
        foreach ($estadosDisponibles as $estado) {
            $query = Activo::where('estado', $estado->id);
            if ($tipoDeActivo) {
            $query->where('tipo_de_activo', $tipoDeActivo);
            }
            $estados[$estado->nombre_estado] = [
                'cantidad' => $query->count(),
                'descripcion' => $estado->descripcion
            ];
        }
        return $estados;
    }

}
