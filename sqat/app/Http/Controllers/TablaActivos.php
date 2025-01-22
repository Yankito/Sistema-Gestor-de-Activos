<?php

namespace App\Http\Controllers;
use App\Models\Activo;

class TablaActivos extends Controller
{
    public function index()
    {
        $activos = Activo::all();
        return view('tablaActivos', compact('activos'));
    }
}