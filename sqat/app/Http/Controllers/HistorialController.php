<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Registro;

class HistorialController extends Controller
{
    // Método para mostrar el historial de cambios
    public function index(Request $request)
    {
        $filterDate = $request->input('filter_date');
        // Iniciar la consulta con las relaciones necesarias
        $query = Registro::with(['persona', 'activo', 'encargadoCambio']);
    
        // Aplicar filtro de fecha si se proporciona
        if ($request->has('filter_date') && !empty($request->filter_date)) {
            $query->whereDate('created_at', $request->filter_date);
        }
    
        // Obtener los registros filtrados
        $registros = $query->get();

    
        // Pasar los registros a la vista
        return view('historial', compact('registros', 'filterDate'));
    }
    
}