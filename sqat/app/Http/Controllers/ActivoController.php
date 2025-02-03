<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use Illuminate\Http\Request;

class ActivoController extends Controller
{
    // Obtener todos los activos
    public function registro()
    {
        $personas = Persona::all();
        $ubicaciones = Ubicacion::all();
        return view('registrarActivo', compact('personas', 'ubicaciones'));
    }

    // Crear un nuevo activo
    public function store(Request $request){
        try {
            $activo = new Activo();

            $activo->nroSerie = $request->nroSerie;
            $activo->marca = $request->marca;
            $activo->modelo = $request->modelo;
            $activo->tipoDeActivo = $request->tipoDeActivo;
            $activo->estado = 'DISPONIBLE';
            $activo->usuarioDeActivo = NULL;
            $activo->responsableDeActivo = NULL;
            $activo->ubicacion = $request->ubicacion;
            $activo->justificacionDobleActivo = $request->justificacionDobleActivo;
            $activo->precio = $request->precio;
            //dd($request);

            if($request->responsable != NULL){
                $activo->usuarioDeActivo = $request->responsable;
                $activo->responsableDeActivo = $request->responsable;
            }

            $activo->save();
            // Redirigir con un mensaje de éxito
            return redirect()->route('dashboard')->with('success', 'Activo registrado correctamente');
        } catch (\Exception $e) {
            // Si ocurre un error, redirigir con mensaje de error a la página actual
            return back()->withInput()->with('error', 'Hubo un problema al registrar el activo: ' . $e->getMessage());
        }
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
