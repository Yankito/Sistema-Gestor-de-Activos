<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ubicacion;

class UbicacionController extends Controller
{

    public function index(){
        $ubicaciones = Ubicacion::all();
        return view('ubicaciones.administrarUbicacion', compact('ubicaciones'));
    }
    public function registro()
    {
        return view('ubicaciones.registrarUbicacion');
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

    public function update(Request $request){
        $request->validate([
            'sitio' => 'required|string|max:255',
            'soporte_ti' => 'required|string|max:255',
            'latitud' => 'required|numeric',
            'longitud' => 'required|numeric',
        ]);

        Ubicacion::where('id', $request->id)->update([
            'sitio' => $request->sitio,
            'soporte_ti' => $request->soporte_ti,
            'latitud' => $request->latitud,
            'longitud' => $request->longitud,
        ]);

        return redirect()->route('ubicaciones')->with('success', 'Ubicación actualizada exitosamente.');
    }

    public function modificar()
    {
        $ubicacion = Ubicacion::find(request('id'));
        return view('ubicaciones.modificarUbicacion', compact('ubicacion'));
    }

    public function eliminar($id) {
        // Verifica si la ubicación existe antes de eliminarla
        $ubicacion = Ubicacion::find($id);

        if (!$ubicacion) {
            return redirect()->route('ubicaciones')->with('error', 'Ubicación no encontrada.');
        }

        $ubicacion->delete();

        return redirect()->route('ubicaciones')->with('success', 'Ubicación eliminada exitosamente.');
    }
}
