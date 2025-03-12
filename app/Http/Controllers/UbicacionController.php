<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Ubicacion;
use Vinkla\Hashids\Facades\Hashids;

class UbicacionController extends Controller
{
    private const NUMERIC_RULE = 'required|numeric';
    const STRING_RULE = 'required|string|max:100';

    public function index(){
        $ubicaciones = Ubicacion::all();
        return view('ubicaciones.administrarUbicacion', compact('ubicaciones'));
    }
    public function registro()
    {
        // Verificar si el usuario es administrador
        if (!auth()->user()->es_administrador) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        }
        return view('ubicaciones.registrarUbicacion');
    }

    public function store(Request $request){
        $request->validate([
            'sitio' => self::STRING_RULE,
            'soporte_ti' => self::STRING_RULE,
            'latitud' => self::NUMERIC_RULE,
            'longitud' => self::NUMERIC_RULE,
        ]);

        // comprobar que no se repita el nombre de la ubicación
        if (Ubicacion::where('sitio', strtoupper($request->sitio))->exists()) {
            return redirect()->route('ubicaciones')->with('error', 'Ubicación ya se encuentra registrada.');
        }

        $request->merge([
            'sitio' => strtoupper($request->sitio),
            'soporte_ti' => strtoupper($request->soporte_ti),
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
            'sitio' => self::STRING_RULE,
            'soporte_ti' => self::STRING_RULE,
            'latitud' => self::NUMERIC_RULE,
            'longitud' => self::NUMERIC_RULE,
        ]);

        $request->merge([
            'sitio' => strtoupper($request->sitio),
            'soporte_ti' => strtoupper($request->soporte_ti),
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

    public function eliminar($hashed_id) {
        // Desencriptar el ID
        $decoded = Hashids::decode($hashed_id);

        if (empty($decoded)) {
            return redirect()->route('ubicaciones')->with('error', 'ID inválido.');
        }

        $id = $decoded[0];

        // Verifica si la ubicación existe antes de eliminarla
        $ubicacion = Ubicacion::find($id);

        if (!$ubicacion) {
            return redirect()->route('ubicaciones')->with('error', 'Ubicación no encontrada.');
        }

        $ubicacion->delete();

        return redirect()->route('ubicaciones')->with('success', 'Ubicación eliminada exitosamente.');
    }
}
?>
