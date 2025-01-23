<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Persona;
use Illuminate\Http\Request;

class RegistrarActivoController extends Controller
{
    // Obtener todos los activos
    public function index()
    {
        $personas = Persona::all();
        return view('registrarActivo', compact('personas'));
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
        $activo->docking = false;
        $activo->parlanteJabra = false;
        $activo->discoDuroExt = false;
        $activo->impresoraExclusiva = false;
        $activo->monitor = false;
        $activo->mouse = false;
        $activo->teclado = false;

        $accesorios[] = $request->accesorios;
        $activo = $this->seleccionarAccesorios($activo, $accesorios);

        $activo->justificacionDobleActivo = $request->justificacionDobleActivo;
        $activo->precio = $request->precio;

        $activo->save();

        return redirect('/dashboard')->with('success', 'Activo registrado correctamente');
    }


    public function seleccionarAccesorios(Activo $activo, array $accesorios){
        foreach ($accesorios as $accesorio){

            switch ($accesorio){
                case "docking":
                    $activo->docking = true;
                    break;
                case "parlanteJabra":
                    $activo->parlanteJabra = true;
                    break;
                case "discoDuroExte":
                    $activo->discoDuroExt = true;
                    break;
                case "impresoExclusiva":
                    $activo->impresoraExclusiva = true;
                    break;
                case "monitor":
                    $activo->monitor = true;
                    break;
                case "mouse":
                    $activo->mouse = true;
                    break;
                case "teclado":
                    $activo->telefono = true;
                    break;
            }
        }
        return $activo;
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
