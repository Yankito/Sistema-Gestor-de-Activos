<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ActivoController extends Controller
{
    // Obtener todos los activos
    public function registro()
    {
        $personas = Persona::all();
        $ubicaciones = Ubicacion::all();
        return view('activos.registrarActivo', compact('personas', 'ubicaciones'));
    }

    // Crear un nuevo activo
    public function store(Request $request){
        try {
            $activo = new Activo();

            $activo->nro_serie = $request->nro_serie;
            $activo->marca = $request->marca;
            $activo->modelo = $request->modelo;
            $activo->tipo_de_activo = $request->tipo_de_activo;
            $activo->estado = 'DISPONIBLE';
            $activo->usuario_de_activo = NULL;
            $activo->responsable_de_activo = NULL;
            $activo->ubicacion = $request->ubicacion;
            $activo->justificacion_doble_activo = $request->justificacion_doble_activo;
            $activo->precio = $request->precio;

            $activo->save();
            if($request->responsable != NULL){
                $activo->usuario_de_activo = $request->responsable;
                $activo->responsable_de_activo = $request->responsable;

                $registro = new Registro();
                $registro->persona = $request->responsable;
                $registro->activo = $activo->id;
                $registro->tipo_cambio = 'ASIGNACION';
                $registro->encargado_cambio = Auth::user()->id;
                $registro->save();
            }
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
        //dd($request->all(), $request->responsable_de_activo);
        $data = $request->all();
        $data['usuario_de_activo'] = $request->responsable_de_activo;
        $activo->update($data);
        return redirect()->back()->with('success', 'Activo actualizado correctamente.');
    }

    // Eliminar un activo
    public function destroy($id)
    {
        Activo::destroy($id);
        return response()->json(null, 204);
    }

    public function editar($id){


        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacion')->findOrFail($id);
        $ubicaciones = Ubicacion::all();
        $personas = Persona::all();

        return view('activos.editarActivo', compact('activo','ubicaciones','personas'));
    }
}
