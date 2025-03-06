<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TipoActivo; // Asegúrate de importar el modelo
use App\Models\CaracteristicaAdicional;


class CrearTipoActivoController extends Controller
{
    public function index()
    {
        // Obtener todos los tipos de activos desde la base de datos
        $tiposActivo = TipoActivo::all();

        // Pasar los datos a la vista
        return view('crearTipoActivo', compact('tiposActivo'));
    }

    public function store(Request $request)
    {
        // Validar los datos del formulario
        $request->validate([
            'nombre' => 'required|string|max:255|unique:tipo_activo,nombre',
        ]);



        // Crear un nuevo tipo de activo
        $tipoActivo = TipoActivo::create([
            'nombre' => strtoupper($request->nombre),
        ]);

        $caracteristicasAdicionales = $request->caracteristicasAdicionales;

        //Agregar las caracteristicas adicionales
        foreach ($caracteristicasAdicionales as $caracteristica) {
            CaracteristicaAdicional::create([
                'tipo_activo_id' => $tipoActivo->id,
                'nombre_caracteristica' => strtoupper($caracteristica),
            ]);
        }
        // Redirigir con un mensaje de éxito
        return redirect()->route('tipos-activo.index')->with('success', 'Tipo de activo registrado correctamente.');
    }

}
