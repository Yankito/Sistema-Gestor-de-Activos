<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Activo;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Traits\ImportarTrait;
use App\Services\ImportarExcelService;

class ImportarController extends Controller
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
        return view('importar.importar');
    }

    public function importExcel(Request $request)
    {
        $this->importarExcelService->validarArchivo($request);

        return $this->importarExcelService->manejarTransaccion(function () use ($request) {
            $spreadsheet = IOFactory::load($request->file('archivo_excel')->getPathname());
            $hoja = $spreadsheet->getActiveSheet();
            $datos = $hoja->toArray(null, true, true, true);

            $asignaciones = [];
            $errores = [];

            foreach ($datos as $index => $fila) {
                if ($index == 1 || $this->filaVacia($fila, 'A', 'E')) {
                    continue;
                }

                $fila['A'] = strtoupper($fila['A']);
                $fila['B'] = strtoupper($fila['B']);
                $fila['C'] = strtoupper($fila['C']);
                $fila['D'] = strtoupper($fila['D']);
                $responsableUser = $this->eliminarTildesYMayusculas($fila['A']);
                $usuarioUser = $this->eliminarTildesYMayusculas($fila['B']);
                $nroSerie = $this->eliminarTildesYMayusculas($fila['C']);
                $estadoExcel = $this->eliminarTildesYMayusculas($fila['D']);
                $justificacion = $fila['E'];

                // Buscar el activo
                $activo = Activo::where('nro_serie', $nroSerie)->first();
                if (!$activo) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "Activo con número de serie '$nroSerie' no encontrado."
                    ];
                    continue;
                }

                // Buscar el estado
                $estado = DB::table('estados')->where('nombre_estado', $estadoExcel)->first();
                if (!$estado) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "Estado '$estadoExcel' no encontrado en la base de datos."
                    ];
                    continue;
                }

                // Manejar el responsable
                if (!empty($responsableUser)) {
                    // Si se proporciona un responsable en el archivo, buscarlo
                    $responsable = Persona::where('user', $responsableUser)->first();
                    if (!$responsable) {
                        $errores[] = [
                            'fila' => $fila,
                            'motivo' => "Responsable '$responsableUser' no encontrado."
                        ];
                        continue;
                    }
                    // Actualizar el responsable del activo
                    $activo->responsable_de_activo = $responsable->id;
                } else {
                    // Si no se proporciona un responsable, mantener el responsable actual (si existe)
                    if (!$activo->responsable_de_activo) {
                        $errores[] = [
                            'fila' => $fila,
                            'motivo' => "El activo no tiene un responsable actual y no se proporcionó uno en el archivo."
                        ];
                        continue;
                    }
                    // No es necesario actualizar el responsable, se mantiene el actual
                }

                // Buscar el usuario
                if (!empty($usuarioUser)) {
                    $usuario = Persona::where('user', $usuarioUser)->first();
                    if (!$usuario) {
                        $errores[] = [
                            'fila' => $fila,
                            'motivo' => "Usuario '$usuarioUser' no encontrado."
                        ];
                        continue;
                    }
                } else {
                    $usuario = null;
                }

                // Actualizar el estado y la justificación del activo
                $activo->estado = $estado->id;
                $activo->justificacion_doble_activo = $justificacion ?: null;

                // Actualizar la ubicación del activo a la ubicación del responsable (si existe)
                if ($activo->responsable_de_activo) {
                    $responsable = Persona::find($activo->responsable_de_activo);
                    if ($responsable) {
                        $activo->ubicacion = $responsable->ubicacion;
                    }
                }

                $activo->save();

                // Crear o actualizar la asignación en la tabla asignaciones
                if ($usuario) {
                    $activo->usuarioDeActivo()->syncWithoutDetaching([$usuario->id]);
                }

                $asignaciones[] = [
                    'responsable' => $activo->responsable_de_activo ? strtoupper(Persona::find($activo->responsable_de_activo)->user) : null,
                    'usuario_activo' => $usuario ? strtoupper($usuario->user) : null,
                    'numero_serie' => $activo->nro_serie,
                    'estado' => $estado->nombre_estado,
                    'justificacion' => $activo->justificacion_doble_activo,
                ];
            }

            // Crear registro en el historial
            $this->crearRegistro('IMPORTÓ ASIGNACIONES');

            return view('importar.importar', compact('datos', 'asignaciones', 'errores'))
                ->with('success', 'Datos importados correctamente.');
        });
    }
}
?>
