<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Activo;
use App\Models\Persona;
use App\Models\Ubicacion;


class DashboardController extends Controller
{
    public function index()
    {
        $cantidadActivos = Activo::count();
        $cantidadPersonas = Persona::count();
        $cantidadUbicaciones = Ubicacion::count();

        // Pasar el usuario a la vista
        return view('dashboard', compact('cantidadActivos','cantidadPersonas','cantidadUbicaciones'));
    }
}
