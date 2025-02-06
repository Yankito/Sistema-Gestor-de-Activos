<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class   ActivoController extends Controller
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
            $activo->estado = 1;
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
        //dd($data, $activo);

        $registroNuevoResponsable = new Registro();
        $registroNuevoResponsable->persona = $request->responsable_de_activo;
        $registroNuevoResponsable->activo = $activo->id;
        $registroNuevoResponsable->tipo_cambio = 'ASIGNACION';
        $registroNuevoResponsable->encargado_cambio = Auth::user()->id;
        $registroNuevoResponsable->save();
        $data['estado'] = 4;


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
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($id);
        $ubicaciones = Ubicacion::all();
        $personas = Persona::all();
        return view('activos.editarActivo', compact('activo','ubicaciones','personas'));
    }

    public function deshabilitar(Request $request, $id){
        $activo = Activo::findOrFail($id);
        $activo->estado = $request->estado;
        if($activo->responsable_de_activo){
            $registroAntiguoResponsable = new Registro();
            $registroAntiguoResponsable->persona = $activo->responsable_de_activo;
            $registroAntiguoResponsable->activo = $id;
            $registroAntiguoResponsable->tipo_cambio = 'DESVINCULACION';
            $registroAntiguoResponsable->encargado_cambio = Auth::user()->id;
            $registroAntiguoResponsable->save();
            $activo->responsable_de_activo = NULL;
            $activo->usuario_de_activo = NULL;
        }

        $activo->update();
        return redirect()->back()->with('success','Activo deshabilitado correctamente.');
    }

    public function cambiarEstado(Request $request){
        $activo = Activo::findOrFail($request->activo_id);
        if( $activo->estado == 7){
            $activo->usuario_de_activo = NULL;
            $activo->responsable_de_activo = NULL;
        }
        if( $activo->nuevo_estado == 7){
            $registroAntiguoResponsable = new Registro();
            $registroAntiguoResponsable->persona = $activo->responsable_de_activo;
            $registroAntiguoResponsable->activo = $request->activo_id;
            $registroAntiguoResponsable->tipo_cambio = 'DESVINCULACION';
            $registroAntiguoResponsable->encargado_cambio = Auth::user()->id;
            $registroAntiguoResponsable->save();
            $activo->responsable_de_activo = NULL;
            $activo->usuario_de_activo = NULL;
        }
        $activo->estado = $request->nuevo_estado;



        $activo->update();
        return redirect()->back()->with('success', 'Estado cambiado a ' . $activo->estadoRelation->nombre_estado);
    }
}
