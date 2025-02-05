<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Persona;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;

class ImportarController extends Controller
{
    public function index()
    {
        return view('importar');
    }
    private function convertirFecha($fecha)
    {
        if (!$fecha) {
            return null; // Si la fecha está vacía, devolver NULL
        }

        $fecha = trim($fecha);
        
        // Si el formato es con barras (1/12/2025)
        if (preg_match('/^\d{1,2}\/\d{1,2}\/\d{4}$/', $fecha)) {
            $partes = explode('/', $fecha);
            return "{$partes[2]}-{$partes[1]}-{$partes[0]}"; // Convertir a YYYY-MM-DD
        }

        // Si el formato es con guiones (ya en formato esperado)
        if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
            return $fecha;
        }

        return null; // En caso de formato inválido
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
            foreach ($datos as $index => $fila) {
                if ($index == 1) continue; // Saltar encabezados

                $persona = Persona::create([
                    'rut' => $fila['A'],
                    'nombre_usuario' => $fila['B'],
                    'nombres' => $fila['C'],
                    'primer_apellido' => $fila['D'],
                    'segundo_apellido' => $fila['E'] ?? null,
                    'supervisor' => $fila['F'],
                    'empresa' => $fila['G'],
                    'estado_empleado' => filter_var($fila['H'], FILTER_VALIDATE_BOOLEAN),
                    'centro_costo' => $fila['I'],
                    'denominacion' => $fila['J'],
                    'titulo_puesto' => $fila['K'],
                    'fecha_inicio' => $this->convertirFecha($fila['L']),
                    'usuario_ti' => filter_var($fila['M'], FILTER_VALIDATE_BOOLEAN),
                    'ubicacion' => $fila['N']
                ]);

                Activo::create([
                    'nro_serie' => $fila['O'],
                    'marca' => $fila['P'],
                    'modelo' => $fila['Q'],
                    'tipo_de_activo' => $fila['R'],
                    'estado' => $fila['S'],
                    'usuario_de_activo' => $persona -> id ?? null,
                    'responsable_de_activo' => $persona -> id ?? null,
                    'precio' => $fila['V'],
                    'ubicacion' => $fila['N'], // La misma ubicación de la persona
                    'justificacion_doble_activo' => $fila['W'] ?? null,
                ]);
            }
            DB::commit();
            return back()->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}
