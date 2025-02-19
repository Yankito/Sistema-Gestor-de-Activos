<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportarController extends Controller
{
    public function index()
    {
        return view('/importar');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,csv'
        ]);

        $archivo = $request->file('archivo_excel');
        $spreadsheet = IOFactory::load($archivo->getPathname());
        $datos = $spreadsheet->getActiveSheet()->toArray();
        $errores = [];
        $asignaciones = [];

        foreach ($datos as $key => $fila) {
            if ($key === 0) continue; // Saltar encabezado

            // Verificar si la fila está en blanco
            if (empty(array_filter($fila))) {
                break; // Detener la importación si se encuentra una línea en blanco
            }

            $responsableUser = strtoupper(trim($fila[0]));
            $usuarioUser = strtoupper(trim($fila[1]));

            $responsable = Persona::where('user', $responsableUser)->first();
            $usuario = Persona::where('user', $usuarioUser)->first();
            $activo = Activo::where('nro_serie', strtoupper(trim($fila[2])))->first();

            $estadoExcel = strtoupper(trim($fila[3]));
            $estadoExcel = str_replace(
                ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'],
                ['A', 'E', 'I', 'O', 'U', 'N'],
                $estadoExcel
            );

            $estado = DB::table('estados')->where('nombre_estado', $estadoExcel)->first();
            if (!$estado) {
                $errores[] = "Fila $key: Estado '$estadoExcel' no encontrado en la base de datos.";
                continue;
            }

            // Verificar si el estado requiere responsable y usuario
            $estadosSinResponsableUsuario = ['ADQUIRIDO', 'PREPARACION', 'DISPONIBLE', 'PERDIDO', 'ROBADO', 'PARA BAJA', 'DONADO', 'VENDIDO'];
            if (!in_array($estadoExcel, $estadosSinResponsableUsuario)) {
                if (!$responsable) {
                    $errores[] = "Fila $key: Responsable '$responsableUser' no encontrado.";
                }
                if (!$usuario) {
                    $errores[] = "Fila $key: Usuario '$usuarioUser' no encontrado.";
                }
                if (!$responsable || !$usuario) {
                    continue;
                }
            }

            if ($activo) {
                $activo->responsable_de_activo = $responsable ? $responsable->id : null;
                $activo->usuario_de_activo = $usuario ? $usuario->id : null;
                $activo->estado = $estado->id;
                $activo->justificacion_doble_activo = trim($fila[4]) ?: null;

                // Actualizar la ubicación del activo a la ubicación del responsable
                if ($responsable) {
                    $activo->ubicacion = $responsable->ubicacion;
                }

                $activo->save();

                $asignaciones[] = [
                    'responsable' => $responsable ? $responsable->user : null,
                    'usuario_activo' => $usuario ? $usuario->user : null,
                    'numero_serie' => $activo->nro_serie,
                    'estado' => $estado->nombre_estado,
                    'justificacion' => $activo->justificacion_doble_activo,
                    'ubicacion' => $activo->ubicacion,
                ];
            } else {
                $errores[] = "Fila $key: Activo con número de serie '{$fila[2]}' no encontrado.";
            }
        }

        return redirect()->back()->with(['errores' => $errores, 'asignaciones' => $asignaciones]);
    }
}