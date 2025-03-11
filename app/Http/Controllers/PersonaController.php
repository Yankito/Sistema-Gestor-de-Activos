<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Registro;
use App\Models\Asignacion;
use Illuminate\Support\Facades\Auth;

class PersonaController extends Controller
{
    // Mostrar todos los registros de personas
    public function registro()
    {
        // Verificar si el usuario es administrador
        if (!auth()->user()->es_administrador) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        } else {
            $activos = Activo::all();
            $ubicaciones = Ubicacion::all();
            $personas = Persona::all();

            // Devolver una vista con la lista de personas
            return view('personas.registrarPersona', compact('activos', 'ubicaciones', 'personas'));
        }
    }

    // Mostrar el formulario para crear una nueva persona
    public function create()
    {
        $ubicaciones = Ubicacion::all(); // Cargar ubicaciones para el formulario
        return view('personas.registrarPersona', compact('ubicaciones'));
    }

    // Almacenar una nueva persona en la base de datos
    public function store(Request $request)
    {
        try {
            // Validar la solicitud
            $request->validate([
                'rut' => [
                    'required',
                    'string',
                    'max:15',
                    'regex:/^\d{7,8}-[\dkK]$/',
                    function ($attribute, $value, $fail) {
                        if ($value !== '11111111-1' && Persona::where('rut', $value)->exists()) {
                            $fail('El campo rut debe ser único.');
                        }
                    },
                ],
                'user' => 'required|string|max:100',
                'nombres' => 'required|string|max:100',
                'primer_apellido' => 'required|string|max:100',
                'segundo_apellido' => 'nullable|string|max:100',
                'nombre_empresa' => 'required|string|max:100',
                'fecha_ing' => 'required|date',
                'cargo' => 'required|string|max:100',
                'ubicacion' => 'nullable|exists:ubicaciones,id',
                'correo' => 'required|string|max:100'
            ], [
                'rut.regex' => 'El campo rut debe ser un rut válido',
            ]);

            // Establecer valor predeterminado para estado_empleado si no se proporciona
            $nombre_completo = $request->primer_apellido . ' ' . $request->segundo_apellido . ' ' . $request->nombres;
            $data = $request->all();
            $data['nombre_completo'] = $nombre_completo;
            $data['estado_empleado'] = $data['estado_empleado'] ?? true;
            $data['user'] = strtoupper($data['user']);
            //dd($data);
            //dd($request, $data);
            // Crear una nueva persona con los datos validados
            Persona::create($data);

            if ($request->activo == null) {
                // Redirigir con un mensaje de éxito
                return redirect()->route('dashboard')->with('success', 'Persona registrada correctamente');
            }

            // Asignar persona a activo de tal numero de serie
            $activo = Activo::where('id', $request->activo)->first();
            if (!$activo) {
                throw new \Exception('El activo no se encontró.');
            }

            $idPersona = Persona::where('rut', $request->rut)->first()->id;
            $activo->estado = 4;
            $activo->ubicacion = $request->ubicacion;

            // Asignar responsable a activo de tal numero de serie
            if ($request->has('responsable')) {
                $activo->responsable_de_activo = $request->responsable;
                Asignacion::create([
                    'id_persona' => $idPersona,
                    'id_activo' => $activo->id,
                ]);
            } else {
                $activo->responsable_de_activo = $idPersona;
            }

            $activo->update();

            $registro = new Registro();
            $registro->persona = $activo->responsable_de_activo;
            $registro->activo = $activo->id;
            $registro->tipo_cambio = 'ASIGNACION';
            $registro->encargado_cambio = Auth::user()->id;
            $registro->save();

            if (!empty($data['activosAdicionales']) && is_array($data['activosAdicionales'])) {
                foreach ($data['activosAdicionales'] as $activoAdicional) {
                    $activoAdicional = json_decode($activoAdicional, true);
                    $id = $activoAdicional['id'];
                    $activoAdicional = Activo::where('id', $id)->first();
                    if ($activoAdicional) {
                        $activoAdicional->estado = 4;
                        if ($request->has('responsable')) {
                            $activoAdicional->responsable_de_activo = $request->responsable;
                            Asignacion::create([
                                'id_persona' => $idPersona,
                                'id_activo' => $activoAdicional->id,
                            ]);
                        } else {
                            $activoAdicional->responsable_de_activo = $idPersona;
                        }
                        $activoAdicional->justificacion_doble_activo = $data['justificaciones'][$id] ?? null;
                        $activoAdicional->update();

                        $registroAdicional = new Registro();
                        $registroAdicional->persona = $activo->responsable_de_activo;
                        $registroAdicional->activo = $activo->id;
                        $registroAdicional->tipo_cambio = 'ASIGNACION';
                        $registroAdicional->encargado_cambio = Auth::user()->id;
                        $registroAdicional->save();
                    }
                }
            }
            // Redirigir con un mensaje de éxito
            return redirect()->route('dashboard')->with('success', 'Persona registrada correctamente');
        } catch (\Exception $e) {
            // Si ocurre un error, redirigir con mensaje de error a la página actual
            return back()->withInput()->with('error', 'Hubo un problema al registrar la persona o asignar el activo: ' . $e->getMessage());
        }
    }

    // Mostrar una persona específica por su ID
    public function show($id)
    {
        $persona = Persona::findOrFail($id);
        return view('personas.show', compact('persona'));
    }

    // Mostrar el formulario para editar una persona existente
    public function edit($id)
    {
        $persona = Persona::findOrFail($id);
        $ubicaciones = Ubicacion::all(); // Cargar ubicaciones para el formulario
        return view('personas.edit', compact('persona', 'ubicaciones'));
    }

    // Actualizar la persona en la base de datos
    public function update(Request $request, $id)
    {
        $request->validate([
            'rut' => [
                'required',
                'string',
                'max:15',
                'regex:/^\d{7,8}-[\dkK]$/',
                function ($attribute, $value, $fail) use ($id) {
                    if ($value !== '11111111-1' && Persona::where('rut', $value)->where('id', '!=', $id)->exists()) {
                        $fail('El campo rut debe ser único.');
                    }
                },
            ],
            'nombre_completo' => 'required|string|max:100',
            'nombre_empresa' => 'required|string|max:100',
            'estado_empleado' => 'required|boolean',
            'fecha_ing' => 'required|date',
            'fecha_ter' => 'required|date',
            'cargo' => 'required|string|max:100',
            'ubicacion' => 'nullable|exists:ubicaciones,id',
            'correo' => 'required|string|max:100'
        ], [
            'rut.regex' => 'El campo rut debe ser un rut válido',
        ]);

        $persona = Persona::findOrFail($id);

        // Actualizar con los datos validados
        $data = $request->all();
        $persona->update($data);

        return redirect()->route('personas.index')->with('success', 'Persona actualizada correctamente');
    }

    // Eliminar una persona de la base de datos
    public function destroy($id)
    {
        $persona = Persona::findOrFail($id);
        $persona->delete();

        return redirect()->route('personas.index')->with('success', 'Persona eliminada correctamente');
    }

    public function checkRut($rut)
    {
        $persona = Persona::where('rut', $rut)->first();
        return response()->json(['exists' => $persona !== null]);
    }
}
