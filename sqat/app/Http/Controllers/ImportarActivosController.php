<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;

class ImportarActivosController extends Controller
{
    public function index()
    {
        return view('/importarActivos');
    }

    private function eliminarTildesYMayusculas($cadena)
    {
        $cadena = strtoupper($cadena);
        $buscar = ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'];
        $reemplazar = ['A', 'E', 'I', 'O', 'U', 'N'];
        return str_replace($buscar, $reemplazar, $cadena);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls'
        ]);
    
        $archivo = $request->file('archivo_excel');
        $spreadsheet = IOFactory::load($archivo->getPathname());
        $hoja = $spreadsheet->getActiveSheet();
        $datos = $hoja->toArray(null, true, true, true);
    
        DB::beginTransaction();
        try {
            $activos = [];
            $errores = [];
    
            foreach ($datos as $index => $fila) {
                if ($index == 1) continue; // Saltar encabezados
    
                // Verificar si la fila está vacía
                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E']) && empty($fila['F']) && empty($fila['G']) && empty($fila['H'])) {
                    continue;
                }
    
                // Convertir la ubicación a mayúsculas y eliminar tildes
                $ubicacion = $this->eliminarTildesYMayusculas($fila['F']);
                $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();
    
                if (!$ubicacionExistente) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "La ubicación '{$ubicacion}' no existe en la base de datos."
                    ];
                    continue;
                }
    
                $ubicacionId = $ubicacionExistente->id;
    
                // Obtener el ID del estado
                $estadoNombre = 'Adquirido'; // Estado predefinido como 'Adquirido'
                $estado = DB::table('estados')->where('nombre_estado', $estadoNombre)->first();
                $estadoId = $estado->id;
                
                if(Activo::where('nro_serie', $fila['A'])->exists()) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "El activo con número de serie '{$fila['A']}' ya existe en la base de datos."
                    ];
                    continue;
                }
    
                // Crear el activo
                Activo::create([
                    'nro_serie' => $fila['A'],
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $fila['D'],
                    'estado' => $estadoId,
                    'usuario_de_activo' => null,
                    'responsable_de_activo' => null,
                    'precio' => $fila['E'],
                    'ubicacion' => $ubicacionId,
                    'justificacion_doble_activo' => null
                ]);
    
                $activos[] = [
                    'nro_serie' => $fila['A'],
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $fila['D'],
                    'estado' => $estadoId,
                    'usuario_de_activo' => null,
                    'responsable_de_activo' => null,
                    'precio' => $fila['E'],
                    'ubicacion' => $ubicacionId,
                    'justificacion_doble_activo' => null
                ];
            }
    
            DB::commit();
            return view('importarActivos', compact('datos', 'activos', 'errores'))->with('success', 'Activos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}
