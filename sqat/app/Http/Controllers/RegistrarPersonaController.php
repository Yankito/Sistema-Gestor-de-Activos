<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Ubicacion;

class RegistrarPersonaController extends Controller
{
    // Mostrar todos los registros de personas
    public function index()
    {
        $activos = Activo::all();
        $ubicaciones = Ubicacion::all();
        $personas = Persona::all();

        // Devolver una vista con la lista de personas
        return view('registrarPersona', compact('activos','ubicaciones', 'personas'));
    }

    // Mostrar el formulario para crear una nueva persona
    public function create()
    {
        $ubicaciones = Ubicacion::all(); // Cargar ubicaciones para el formulario
        return view('registrarPersona', compact('ubicaciones'));
    }

    // Almacenar una nueva persona en la base de datos
    public function store(Request $request){
        try {
            // Validar la solicitud
            $request->validate([
                'rut' => 'required|string|max:15|unique:personas',
                'nombreUsuario' => 'required|string|max:50',
                'nombres' => 'required|string|max:50',
                'primerApellido' => 'required|string|max:25',
                'segundoApellido' => 'nullable|string|max:25',
                'supervisor' => 'nullable|string|max:60',
                'empresa' => 'required|string|max:60',
                'estadoEmpleado' => 'nullable|boolean',
                'centroCosto' => 'required|string|max:50',
                'denominacion' => 'required|string|max:60',
                'tituloPuesto' => 'required|string|max:60',
                'fechaInicio' => 'required|date',
                'usuarioTI' => 'required|boolean',
                'ubicacion' => 'nullable|exists:ubicaciones,id',
                'activo' => 'required|exists:activos,nroSerie',
                'responsable'=> 'nullable|string|max:15',
            ]);

            // Establecer valor predeterminado para estadoEmpleado si no se proporciona
            $data = $request->all();
            $data['estadoEmpleado'] = $data['estadoEmpleado'] ?? true;

            // Crear una nueva persona con los datos validados
            Persona::create($data);

            // Asignar persona a activo de tal numero de serie
            $activo = Activo::where('nroSerie', $request->activo)->first();
            if (!$activo) {
                throw new \Exception('El activo no se encontró.');
            }

            $activo->usuarioDeActivo = $request->rut;
            $activo->estado = 'ASIGNADO';

            // Asignar responsable a activo de tal numero de serie
            $activo->responsableDeActivo = $request->has('responsable') ? $request->responsable : $request->rut;
            $activo->update();

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
            'rut' => 'required|string|max:15|unique:personas,rut,' . $id,
            'nombreUsuario' => 'required|string|max:50',
            'nombres' => 'required|string|max:50',
            'primerApellido' => 'required|string|max:25',
            'segundoApellido' => 'nullable|string|max:25',
            'supervisor' => 'nullable|string|max:60',
            'empresa' => 'required|string|max:60',
            'estadoEmpleado' => 'nullable|boolean',
            'centroCosto' => 'required|string|max:50',
            'denominacion' => 'required|string|max:60',
            'tituloPuesto' => 'required|string|max:60',
            'fechaInicio' => 'required|date',
            'usuarioTI' => 'required|boolean',
            'ubicacion' => 'nullable|exists:ubicaciones,id',
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
}
