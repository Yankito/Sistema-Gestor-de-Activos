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

    private function eliminarTildesYMayusculas($cadena)
    {
        $cadena = strtoupper($cadena);
        $buscar = ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'];
        $reemplazar = ['A', 'E', 'I', 'O', 'U', 'N'];
        return str_replace($buscar, $reemplazar, $cadena);
    }
    //funcion que recibe si, Si,SI, no, No,NO y devuelve 1 o 0
    private function convertirBoolean($valor)
    {
        $valor = strtoupper($valor);
        if ($valor == 'SI' || $valor == 'SÍ') {
            return 1;
        }
        if ($valor == 'NO') {
            return 0;
        }
        return null;
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
                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E']) && empty($fila['F']) && empty($fila['G']) && empty($fila['H']) && empty($fila['I']) && empty($fila['J']) && empty($fila['K']) && empty($fila['L']) && empty($fila['M']) && empty($fila['N']) && empty($fila['O']) && empty($fila['P']) && empty($fila['Q']) && empty($fila['R']) && empty($fila['S']) && empty($fila['V'])) {
                    continue;
                }
                // Convertir la ubicación a mayúsculas y eliminar tildes
                $ubicacion = $this->eliminarTildesYMayusculas($fila['N']);
                $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();

                if (!$ubicacionExistente) {
                    throw new \Exception("La ubicación '{$ubicacion}' no existe en la base de datos.");
                }

                // Obtener el ID de la ubicación
                $ubicacionId = $ubicacionExistente->id;

                $persona = Persona::create([
                    'rut' => $fila['A'],
                    'nombre_usuario' => $fila['B'],
                    'nombres' => $fila['C'],
                    'primer_apellido' => $fila['D'],
                    'segundo_apellido' => $fila['E'] ?? null,
                    'supervisor' => $fila['F'],
                    'empresa' => $fila['G'],
                    'estado_empleado' => filter_var($this->convertirEstadoEmpleado($fila['H']), FILTER_VALIDATE_BOOLEAN),
                    'centro_costo' => $fila['I'],
                    'denominacion' => $fila['J'],
                    'titulo_puesto' => $fila['K'],
                    'fecha_inicio' => $this->convertirFecha($fila['L']),
                    'usuario_ti' => filter_var($this->convertirBoolean($fila['M']), FILTER_VALIDATE_BOOLEAN),
                    'ubicacion' => $ubicacionId,
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
