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
                if ($index == 1 || $this->filaVacia($fila)) continue;

                $resultado = $this->procesarFila($fila);
                if ($resultado['error']) {
                    $errores[] = $resultado['mensaje'];
                } else {
                    $asignaciones[] = $resultado['asignacion'];
                }
            }

            $this->registrarImportacion();
            DB::commit();

            return view('importar.importar', compact('datos', 'asignaciones', 'errores'))
                ->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }

    private function filaVacia($fila)
    {
        return empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E']);
    }

    private function procesarFila($fila)
    {
        $responsableUser = $this->eliminarTildesYMayusculas($fila['A']);
        $usuarioUser = $this->eliminarTildesYMayusculas($fila['B']);
        $nroSerie = $this->eliminarTildesYMayusculas($fila['C']);
        $estadoExcel = $this->eliminarTildesYMayusculas($fila['D']);
        $justificacion = $fila['E'];

        $activo = $this->obtenerActivo($nroSerie);
        if (!$activo) return ['error' => true, 'mensaje' => "Activo con número de serie '$nroSerie' no encontrado."];

        $estado = $this->obtenerEstado($estadoExcel);
        if (!$estado) return ['error' => true, 'mensaje' => "Estado '$estadoExcel' no encontrado en la base de datos."];

        $responsable = $this->obtenerPersona($responsableUser, "Responsable");
        if (!$responsable && !empty($responsableUser)) return ['error' => true, 'mensaje' => "Responsable '$responsableUser' no encontrado."];

        $usuario = $this->obtenerPersona($usuarioUser, "Usuario");

        $this->actualizarActivo($activo, $responsable, $usuario, $estado, $justificacion);

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

    private function registrarImportacion()
    {
        Registro::create([
            'activo' => null,
            'persona' => null,
            'tipo_cambio' => 'IMPORTÓ ASIGNACIONES',
            'encargado_cambio' => Auth::user()->id
        ]);
    }
}