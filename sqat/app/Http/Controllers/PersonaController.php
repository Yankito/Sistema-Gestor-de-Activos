<?php

namespace App\Http\Controllers;

use App\Models\Activo;
use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Ubicacion;

class PersonaController extends Controller
{
    // Mostrar todos los registros de personas
    public function index()
    {
        $activos = Activo::all();
        $ubicaciones = Ubicacion::all();

        // Devolver una vista con la lista de personas
        return view('persona', compact('activos','ubicaciones'));
    }

    // Mostrar el formulario para crear una nueva persona
    public function create()
    {
        // Devolver la vista del formulario de creación
        return view('personas.create');
    }

    // Almacenar una nueva persona en la base de datos
    public function store(Request $request)
    {
        // Validar la solicitud
        $request->validate([
            'rut' => 'required|string|max:255|unique:personas',
            'nombreUsuario' => 'required|string|max:255',
            'nombres' => 'required|string|max:255',
            'primerApellido' => 'required|string|max:255',
            'segundoApellido' => 'nullable|string|max:255',
            'supervisor' => 'nullable|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'estadoEmpleado' => 'nullable|string|max:255',
            'centroCosto' => 'nullable|string|max:255',
            'denominacion' => 'nullable|string|max:255',
            'tituloPuesto' => 'nullable|string|max:255',
            'fechaInicio' => 'nullable|date',
            'usuarioTI' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|integer',
        ]);

        // Crear una nueva persona con los datos validados
        Persona::create($request->all());

        // Redirigir con un mensaje de éxito
        return redirect()->route('personas.index')->with('success', 'Persona registrada correctamente');
    }

    // Mostrar una persona específica por su ID
    public function show($id)
    {
        // Buscar la persona por ID o devolver un error 404 si no se encuentra
        $persona = Persona::findOrFail($id);

        // Devolver la vista con los detalles de la persona
        return view('personas.show', compact('persona'));
    }

    // Mostrar el formulario para editar una persona existente
    public function edit($id)
    {
        // Buscar la persona por ID
        $persona = Persona::findOrFail($id);

        // Devolver la vista con el formulario de edición y la persona a editar
        return view('personas.edit', compact('persona'));
    }

    // Actualizar la persona en la base de datos
    public function update(Request $request, $id)
    {
        // Validar la solicitud
        $request->validate([
            'rut' => 'required|string|max:255|unique:personas,rut,' . $id,
            'nombreUsuario' => 'required|string|max:255',
            'nombres' => 'required|string|max:255',
            'primerApellido' => 'required|string|max:255',
            'segundoApellido' => 'nullable|string|max:255',
            'supervisor' => 'nullable|string|max:255',
            'empresa' => 'nullable|string|max:255',
            'estadoEmpleado' => 'nullable|string|max:255',
            'centroCosto' => 'nullable|string|max:255',
            'denominacion' => 'nullable|string|max:255',
            'tituloPuesto' => 'nullable|string|max:255',
            'fechaInicio' => 'nullable|date',
            'usuarioTI' => 'nullable|string|max:255',
            'ubicacion' => 'nullable|integer',
        ]);

        // Buscar la persona a actualizar
        $persona = Persona::findOrFail($id);

        // Actualizar la persona con los nuevos datos
        $persona->update($request->all());

        // Redirigir con un mensaje de éxito
        return redirect()->route('personas.index')->with('success', 'Persona actualizada correctamente');
    }

    // Eliminar una persona de la base de datos
    public function destroy($id)
    {
        // Buscar la persona a eliminar
        $persona = Persona::findOrFail($id);

        // Eliminar la persona
        $persona->delete();

        // Redirigir con un mensaje de éxito
        return redirect()->route('personas.index')->with('success', 'Persona eliminada correctamente');
    }
}
