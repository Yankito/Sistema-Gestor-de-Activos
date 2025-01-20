<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Persona;
use Illuminate\Http\Request;

class ActivoController extends Controller
{
    // Obtener todos los activos
    public function index()
    {
        $personas = Persona::all();
        return view('activo', compact('personas'));
    }

    // Crear un nuevo activo
    public function store(Request $request)
    {
        $activo = new Activo();

        $activo->nroSerie = $request->nroSerie;
        $activo->marca = $request->marca;
        $activo->modelo = $request->modelo;
        $activo->estado = $request->estado;
        $activo->usuarioDeActivo = $request->usuarioDeActivo;
        $activo->responsableDeActivo = $request->responsableDeActivo;
        $activo->docking = $request->has('docking');
        $activo->parlanteJabra = $request->has('parlanteJabra');
        $activo->discoDuroExt = $request->has('discoDuroExt');
        $activo->impresoraExclusiva = $request->has('impresoraExclusiva');
        $activo->monitor = $request->has('monitor');
        $activo->mouse = $request->has('mouse');
        $activo->teclado = $request->has('teclado');
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
