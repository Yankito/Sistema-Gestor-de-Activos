<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ubicacion;

class UbicacionController extends Controller
{
    public function registro()
    {
        // Verificar si el usuario es administrador
        if (!auth()->user()->es_administrador) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        }else{
            return view('ubicaciones.registrarUbicacion');
        }
    }

    public function store(Request $request){
        $request->validate([
            'sitio' => 'required|string|max:255',
            'soporte_ti' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        Ubicacion::create([
            'sitio' => $request->sitio,
            'soporte_ti' => $request->soporte_ti,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        return redirect()->route('dashboard')->with('success', 'Ubicación guardada exitosamente.');
    }
}
