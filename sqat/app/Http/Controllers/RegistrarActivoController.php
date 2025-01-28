<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use Illuminate\Http\Request;

class RegistrarActivoController extends Controller
{
    // Obtener todos los activos
    public function index()
    {
        $personas = Persona::all();
        $ubicaciones = Ubicacion::all();
        return view('registrarActivo', compact('personas', 'ubicaciones'));
    }

    // Crear un nuevo activo
    public function store(Request $request)
    {
        $activo = new Activo();

        $activo->nroSerie = $request->nroSerie;
        $activo->marca = $request->marca;
        $activo->modelo = $request->modelo;
        $activo->tipoActivo = $request->tipoActivo;
        $activo->estado = 'DISPONIBLE';
        $activo->usuarioDeActivo = NULL;
        $activo->responsableDeActivo = NULL;
        $activo->ubicacion = $request->ubicacion;
        $activo->justificacionDobleActivo = $request->justificacionDobleActivo;
        $activo->precio = $request->precio;

        $activo->save();

        return redirect('/dashboard')->with('success', 'Activo registrado correctamente');
    }

    // Obtener un solo activo por su ID
    public function show($id)
    {
        return Activo::findOrFail($id);
    }

    // Actualizar un activo existente
    public function update(Request $request, $id)
    {
        $activo = Activo::findOrFail($id);
        $activo->update($request->all());
        return response()->json($activo, 200);
    }

    // Eliminar un activo
    public function destroy($id)
    {
        Activo::destroy($id);
        return response()->json(null, 204);
    }
}
