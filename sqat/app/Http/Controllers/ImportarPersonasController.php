<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Persona;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;

class ImportarPersonasController extends Controller
{
    private function convertirEstadoEmpleado($valor)
    {   
        $valor = strtoupper($this->eliminarTildesYMayusculas($valor));

        if ($valor === 'ACTIVO') {
            return 1;
        }
        if ($valor === 'TERMINADO') {
            return 0;
        }

        return null; // Devuelve null si el valor no es válido
    }

    public function index()
    {
        return view('importarPersonas');
    }

    private function convertirFecha($fecha)
    {
        if (!$fecha) {
            return null; // Si la fecha está vacía, devolver NULL
        }
    
        $fecha = trim($fecha);
    
        // ✅ Caso 1: Excel almacena la fecha como un número
        if (is_numeric($fecha)) {
            return date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($fecha));
        }
    
        // ✅ Caso 2: Fecha en formato DD/MM/YYYY o D/M/YYYY
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $fecha, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[2], $matches[1]); // Convertir a YYYY-MM-DD
        }
    
        throw new \Exception("Formato de fecha no reconocido: '{$fecha}'");
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
            $personas = [];
            foreach ($datos as $index => $fila) {
                if ($index == 1) continue; // Saltar encabezados
                //verificar si la fila esta vacia
                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E']) && empty($fila['F']) && empty($fila['G']) && empty($fila['H']) && empty($fila['I'])) {
                    continue;
                }
                // Convertir la ubicación a mayúsculas y eliminar tildes
                $ubicacion = $this->eliminarTildesYMayusculas($fila['H']);
                $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();

                if (!$ubicacionExistente) {
                    throw new \Exception("La ubicación '{$ubicacion}' no existe en la base de datos.");
                }

                // Obtener el ID de la ubicación
                $ubicacionId = $ubicacionExistente->id;

                $persona = Persona::create([
                    'rut' => $fila['A'],
                    'nombre_completo' => $fila['B'],
                    'nombre_empresa' => $fila['C'],
                    'estado_empleado' => filter_var($this->convertirEstadoEmpleado($fila['D']), FILTER_VALIDATE_BOOLEAN),
                    'fecha_ing' => date('Y-m-d', strtotime($this->convertirFecha($fila['E']))),
                    'fecha_ter' => $fila['F'] ? date('Y-m-d', strtotime($this->convertirFecha($fila['F']))) : null,
                    'cargo' => $fila['G'],
                    'ubicacion' => $ubicacionId,
                    'correo' => $fila['I']
                ]);
                

                $personas[] = $persona;
            }
            DB::commit();
            return view('importar', compact('datos', 'personas'))->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }

    public function confirmarImportacion()
    {
        return view('confirmar-importacion');
    }
}
