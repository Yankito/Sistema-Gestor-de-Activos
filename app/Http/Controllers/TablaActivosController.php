<?php

namespace App\Http\Controllers;
use App\Models\Activo;
use App\Models\Estado;
use Illuminate\Support\Facades\DB;

class TablaActivosController extends Controller
{
    public function index()
    {
        $activos = Activo::with('usuarioDeActivo', 'responsableDeActivo', 'ubicacionRelation', 'estadoRelation')->get();
        $estados = Estado::all();

        return view('activos.tablaActivos', compact( 'activos', 'estados'));
    }
}
?>
