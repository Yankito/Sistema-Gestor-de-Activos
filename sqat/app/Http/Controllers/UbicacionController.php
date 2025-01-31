<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ubicacion;

class UbicacionController extends Controller
{
    public function registro()
    {
        return view('registrarUbicacion');
    }

    public function store(Request $request){
        $request->validate([
            'sitio' => 'required|string|max:255',
            'soporteTI' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        Ubicacion::create([
            'sitio' => $request->sitio,
            'soporteTI' => $request->soporteTI,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        return redirect()->route('dashboard')->with('success', 'Ubicación guardada exitosamente.');
    }
}
