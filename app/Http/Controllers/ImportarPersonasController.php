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
                if ($index == 1 || $this->filaVacia($fila)) continue;

                $ubicacion = $this->obtenerUbicacion($fila['I'], $errores);
                if (!$ubicacion) continue;

                if ($this->rutExiste($fila['B'])) {
                    $errores[] = $this->crearError($fila, "El RUT '{$fila['B']}' ya existe.");
                    continue;
                }

                $user = $this->obtenerUser($fila['A']);
                if ($this->userExiste($user)) {
                    $errores[] = $this->crearError($fila, "El user '{$user}' ya existe.");
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

                $nuevaPersona = $this->crearPersona($fila, $ubicacion->id, $user);
                $personas[] = $this->formatearPersona($nuevaPersona, $ubicacion);
            }

            // Crear registro en el historial
            $this->crearRegistro('IMPORTÓ PERSONAS');

            return view('importar.importarPersonas', compact('datos', 'personas', 'errores'))
                ->with('success', 'Datos importados correctamente.');
        });
    }

    private function filaVacia($fila)
    {
        return empty(array_filter($fila));
    }

    private function obtenerUbicacion($ubicacion, &$errores)
    {
        $ubicacion = $this->eliminarTildesYMayusculas($ubicacion);
        $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();

        if (!$ubicacionExistente) {
            $errores[] = $this->crearError([], "La ubicación '{$ubicacion}' no existe.");
        }

        return $ubicacionExistente;
    }

    private function rutExiste($rut)
    {
        return $rut !== '11111111-1' && Persona::where('rut', $rut)->exists();
    }

    private function userExiste($user)
    {
        return Persona::where('user', $user)->exists();
    }

    private function obtenerUser($user)
    {
        return strtoupper($user) ?: $this->generarUserProvisional();
    }

    private function crearPersona($fila, $ubicacionId, $user)
    {
        return Persona::create([
            'user' => $user,
            'rut' => $fila['B'],
            'nombre_completo' => $fila['C'],
            'nombre_empresa' => $fila['D'],
            'estado_empleado' => filter_var($this->convertirEstadoEmpleado($fila['E']), FILTER_VALIDATE_BOOLEAN),
            'fecha_ing' => $this->convertirFecha($fila['F']),
            'fecha_ter' => $fila['G'] ? $this->convertirFecha($fila['G']) : null,
            'cargo' => $fila['H'],
            'ubicacion' => $ubicacionId,
            'correo' => $fila['J']
        ]);
    }

    private function formatearPersona($persona, $ubicacion)
    {
        return [
            'user' => $persona->user,
            'rut' => $persona->rut,
            'nombre_completo' => $persona->nombre_completo,
            'nombre_empresa' => $persona->nombre_empresa,
            'estado_empleado' => $persona->estado_empleado ? 'ACTIVO' : 'INACTIVO',
            'fecha_ing' => $persona->fecha_ing,
            'fecha_ter' => $persona->fecha_ter,
            'cargo' => $persona->cargo,
            'ubicacion' => $ubicacion->sitio,
            'correo' => $persona->correo
        ];
    }

    private function crearError($fila, $motivo)
    {
        return ['fila' => $fila, 'motivo' => $motivo];
    }

    private function registrarHistorial()
    {
        Registro::create([
            'activo' => null,
            'persona' => null,
            'tipo_cambio' => 'IMPORTÓ PERSONAS',
            'encargado_cambio' => Auth::id()
        ]);
    }

    private function eliminarTildesYMayusculas($cadena)
    {
        $cadena = strtoupper($cadena);
        return str_replace(['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'], ['A', 'E', 'I', 'O', 'U', 'N'], $cadena);
    }

    private function convertirEstadoEmpleado($valor)
    {
        $valor = strtoupper($this->eliminarTildesYMayusculas($valor));
        return $valor === 'ACTIVO' ? 1 : ($valor === 'TERMINADO' ? 0 : null);
    }

    private function convertirFecha($fecha)
    {
        if (!$fecha) return null;
        $fecha = trim($fecha);

        if (is_numeric($fecha)) {
            return date('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($fecha));
        }

        if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $fecha, $matches)) {
            return sprintf('%04d-%02d-%02d', $matches[3], $matches[1], $matches[2]);
        }

        throw new \Exception("Formato de fecha no reconocido: '{$fecha}'");
    }

    private function generarUserProvisional()
    {
        return 'PROV_' . substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
    }

    public function confirmarImportacion()
    {
        return view('confirmar-importacion');
    }
}