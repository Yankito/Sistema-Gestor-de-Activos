<?php

namespace App\Http\Controllers;

use App\Models\Persona;


class TablaPersonasController extends Controller
{
    // Mostrar todos los registros de personas
    public function index()
    {
        $personas = Persona::all();
        return view('tablaPersonas', compact('personas'));
    }
}
