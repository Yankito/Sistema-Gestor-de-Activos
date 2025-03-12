<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Activo;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarController extends Controller
{
    public function index()
    {
        if (!auth()->user()->es_administrador) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        }
        return view('importar.importar');
    }

    private function eliminarTildesYMayusculas($cadena)
    {
        $buscar = ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'];
        $reemplazar = ['A', 'E', 'I', 'O', 'U', 'N'];
        return str_replace($buscar, $reemplazar, strtoupper($cadena));
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls|max:5120',
        ], [
            'archivo_excel.max' => 'El archivo no debe ser mayor a 5 MB.',
        ]);

        $archivo = $request->file('archivo_excel');
        $spreadsheet = IOFactory::load($archivo->getPathname());
        $hoja = $spreadsheet->getActiveSheet();
        $datos = $hoja->toArray(null, true, true, true);

        DB::beginTransaction();
        try {
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

        return [
            'error' => false,
            'asignacion' => [
                'responsable' => $responsable ? strtoupper($responsable->user) : null,
                'usuario_activo' => $usuario ? strtoupper($usuario->user) : null,
                'numero_serie' => $activo->nro_serie,
                'estado' => $estado->nombre_estado,
                'justificacion' => $activo->justificacion_doble_activo,
            ]
        ];
    }

    private function obtenerActivo($nroSerie)
    {
        return Activo::where('nro_serie', $nroSerie)->first();
    }

    private function obtenerEstado($estadoNombre)
    {
        return DB::table('estados')->where('nombre_estado', $estadoNombre)->first();
    }

    private function obtenerPersona($usuario, $tipo)
    {
        return !empty($usuario) ? Persona::where('user', $usuario)->first() : null;
    }

    private function actualizarActivo($activo, $responsable, $usuario, $estado, $justificacion)
    {
        if ($responsable) {
            $activo->responsable_de_activo = $responsable->id;
            $activo->ubicacion = $responsable->ubicacion;
        }

        $activo->estado = $estado->id;
        $activo->justificacion_doble_activo = $justificacion ?: null;
        $activo->save();

        if ($usuario) {
            $activo->usuarioDeActivo()->syncWithoutDetaching([$usuario->id]);
        }
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
?>
