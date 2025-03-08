<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Registro;
use App\Models\TipoActivo;
use App\Models\Asignacion;
use App\Models\ValorAdicional;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivoController extends Controller
{
    // Obtener todos los activos
    public function registro()
    {
        // Verificar si el usuario es administrador
        if (!auth()->user()->es_administrador) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        }else{
            $personas = Persona::all();
            $ubicaciones = Ubicacion::all();
            $tiposDeActivo = TipoActivo::with('caracteristicasAdicionales')->get();
            return view('activos.registrarActivo', compact('personas', 'ubicaciones', 'tiposDeActivo'));
        }
    }

    // Crear un nuevo activo
    public function store(Request $request)
    {
        try {
            // Crear el activo
            $activo = new Activo();
            $activo->nro_serie = strtoupper($request->nro_serie);
            $activo->marca = strtoupper($request->marca);
            $activo->modelo = $request->modelo;
            $activo->tipo_de_activo = $request->tipo_de_activo;
            $activo->estado = 1; // Estado inicial (por ejemplo, "Adquirido")
            $activo->responsable_de_activo = NULL;
            $activo->ubicacion = $request->ubicacion;
            $activo->justificacion_doble_activo = $request->justificacion_doble_activo;
            $activo->precio = $request->precio;
            if($request->responsable != NULL){
                $activo->responsable_de_activo = $request->responsable;
                $activo->estado = 4; // Cambiar el estado a "Asignado"
                $activo->ubicacion = Persona::findOrFail($request->responsable)->ubicacion;
                $activo->update(); // Actualizar el activo con el responsable

                // Crear el registro de asignación
                $registroAsignacion = new Registro();
                $registroAsignacion->persona = $request->responsable; // Asignar el ID de la persona
                $registroAsignacion->activo = $activo->id; // Asignar el ID del activo recién creado
                $registroAsignacion->tipo_cambio = 'ASIGNACION';
                $registroAsignacion->encargado_cambio = Auth::user()->id; // ID del usuario que realizó el cambio
                $registroAsignacion->save();
            }
            $activo->save();

            if($request->usuarios != NULL){
                foreach ($request->usuarios as $usuarioId) {
                    Asignacion::create([
                        'id_persona' => $usuarioId,
                        'id_activo' => $activo->id
                    ]);
                }
            }

            if($request->caracteristicas != NULL){
                foreach ($request->caracteristicas as $caracteristica => $valor) {
                    if($valor == NULL){
                        continue;
                    }
                    ValorAdicional::create([
                        'id_activo' => $activo->id,
                        'id_caracteristica' => $caracteristica,
                        'valor' => $valor
                    ]);
                }
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
        $activo = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->findOrFail($id);
        //dd($request->all());
        $data = $request->all();
        //dd($data, $activo);

        if($activo->respons_de_activo != $request->responsable_de_activo){
            $registroNuevoResponsable = new Registro();
            $registroNuevoResponsable->persona = $request->responsable_de_activo;
            $registroNuevoResponsable->activo = $activo->id;
            $registroNuevoResponsable->tipo_cambio = 'ASIGNACION';
            $registroNuevoResponsable->encargado_cambio = Auth::user()->id;
            $registroNuevoResponsable->save();
            $data['estado'] = 4;
        }

        if($activo->ubicacion != $request->ubicacion){
            $data['ubicacion'] = $request->ubicacion;
            $persona = Persona::findOrFail($request->responsable_de_activo);
            $persona->ubicacion = $request->ubicacion;
            $persona->update();
        }

        $activo->update($data);
        $activoActualizado = Activo::with('estadoRelation')->findOrFail($id);

        $activo->update();
        return response()->json([
            'success' => true,
            'activoModificado'=> $activoActualizado
        ]);
    }

    // Eliminar un activo
    public function destroy($id)
    {
        Activo::destroy($id);
        return response()->json(null, 204);
    }


}
