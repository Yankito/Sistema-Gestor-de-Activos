<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;
use App\Traits\ImportarTrait;
use App\Services\ImportarExcelService;

class ImportarPersonasController extends Controller
{
    use ImportarTrait;  // Usar el trait

    protected $importarExcelService;

    public function __construct(ImportarExcelService $importarExcelService)
    {
        $this->importarExcelService = $importarExcelService;
    }

    public function index()
    {
        if ($redirect = $this->redirigirSiNoEsAdmin()) {
            return $redirect;
        }
        return view('importar.importarPersonas');
    }

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

    private function convertirFecha($fecha)
    {
        if (!$fecha) {
            return null; // Si la fecha está vacía, devolver NULL
        }

        $fecha = trim($fecha);

        //  Caso 1: Excel almacena la fecha como un número
        if (is_numeric($fecha)) {
            return date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($fecha));
        }

        //  Caso 2: Fecha en formato DD/MM/YYYY o D/M/YYYY
        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $fecha, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[1], $matches[2]); // Convertir a YYYY-MM-DD
        }

        throw new \Exception("Formato de fecha no reconocido: '{$fecha}'");
    }

    private function generarUserProvisional()
    {
        $prefix = 'PROV_'; // Prefijo para identificar que es un user provisional
        $randomString = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10); // Genera una cadena aleatoria de 10 caracteres
        return $prefix . $randomString;
    }

    public function importExcel(Request $request)
    {
        $this->importarExcelService->validarArchivo($request);

        return $this->importarExcelService->manejarTransaccion(function () use ($request) {
            $spreadsheet = IOFactory::load($request->file('archivo_excel')->getPathname());
            $hoja = $spreadsheet->getActiveSheet();
            $datos = $hoja->toArray(null, true, true, true);

            $personas = [];
            $errores = [];

            foreach ($datos as $index => $fila) {
                if ($index == 1) continue; // Saltar encabezados

                // Verificar si la fila está vacía
                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E']) && empty($fila['F']) && empty($fila['G']) && empty($fila['H']) && empty($fila['I'])) {
                    continue;
                }

                $fila['A'] = strtoupper($fila['A']);

                // Convertir la ubicación a mayúsculas y eliminar tildes
                $ubicacion = $this->eliminarTildesYMayusculas($fila['I']);
                $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();

                if (!$ubicacionExistente) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "La ubicación '{$ubicacion}' no existe en la base de datos."
                    ];
                    continue;
                }

                // Obtener el ID de la ubicación
                $ubicacionId = $ubicacionExistente->id;

                // Verificar si el RUT ya existe en la base de datos
                if ($fila['B'] !== '11111111-1' && Persona::where('rut', $fila['B'])->exists()) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "El RUT '{$fila['B']}' ya existe en la base de datos."
                    ];
                    continue;
                }

                // Verificar si el user es 0 y generar un user provisional
                $user = strtoupper($fila['A']);
                if ($user == 0) {
                    $user = $this->generarUserProvisional();
                }

                // Verificar si el user ya existe en la base de datos
                if (Persona::where('user', $user)->exists()) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "El user '{$user}' ya existe en la base de datos."
                    ];
                    continue;
                }

                // Verificar que la fecha no sea null
                if ($fila['F'] == null) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "La fecha de ingreso no puede ser nula."
                    ];
                    continue;
                }

                // Crear la persona
                Persona::create([
                    'user' => $user,
                    'rut' => $fila['B'],
                    'nombre_completo' => $fila['C'],
                    'nombre_empresa' => $fila['D'],
                    'estado_empleado' => filter_var($this->convertirEstadoEmpleado($fila['E']), FILTER_VALIDATE_BOOLEAN),
                    'fecha_ing' => $this->convertirFecha($fila['F']),
                    'fecha_ter' => $fila['F'] ? $this->convertirFecha($fila['G']) : null,
                    'cargo' => $fila['H'],
                    'ubicacion' => $ubicacionId,
                    'correo' => $fila['J']
                ]);

                // Transformar el estado y la ubicación para mostrarlos en la vista
                $estadoEmpleado = $this->convertirEstadoEmpleado($fila['E']);
                $estadoEmpleadoNombre = $estadoEmpleado === 1 ? 'ACTIVO' : ($estadoEmpleado === 0 ? 'INACTIVO' : 'DESCONOCIDO');
                $ubicacionNombre = $ubicacionExistente->sitio;

                $personas[] = [
                    'user' => $user,
                    'rut' => $fila['B'],
                    'nombre_completo' => $fila['C'],
                    'nombre_empresa' => $fila['D'],
                    'estado_empleado' => $estadoEmpleadoNombre, // Mostrar el nombre del estado
                    'fecha_ing' => $this->convertirFecha($fila['F']),
                    'fecha_ter' => $fila['F'] ? $this->convertirFecha($fila['G']) : null,
                    'cargo' => $fila['H'],
                    'ubicacion' => $ubicacionNombre, // Mostrar el nombre de la ubicación
                    'correo' => $fila['J']
                ];
            }

            // Crear registro en el historial
            $this->crearRegistro('IMPORTÓ PERSONAS');

            return view('importar.importarPersonas', compact('datos', 'personas', 'errores'))
                ->with('success', 'Datos importados correctamente.');
        });
    }

    public function confirmarImportacion()
    {
        return view('confirmar-importacion');
    }
}
?>
