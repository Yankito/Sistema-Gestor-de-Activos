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
        // Verificar si el usuario es administrador
        if (!auth()->user()->es_administrador) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        } else {
            return view('/importar');
        }
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
            'archivo_excel' => 'required|mimes:xlsx,csv'
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
                if ($index == 1) continue; // Saltar encabezados

                // Verificar si la fila está vacía
                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E'])) {
                    continue;
                }

                $responsableUser = $this->eliminarTildesYMayusculas($fila['A']);
                $usuarioUser = $this->eliminarTildesYMayusculas($fila['B']);
                $nroSerie = $this->eliminarTildesYMayusculas($fila['C']);
                $estadoExcel = $this->eliminarTildesYMayusculas($fila['D']);
                $justificacion = $fila['E'];

                $responsable = Persona::where('user', $responsableUser)->first();
                $usuario = Persona::where('user', $usuarioUser)->first();
                $activo = Activo::where('nro_serie', $nroSerie)->first();

                $estado = DB::table('estados')->where('nombre_estado', $estadoExcel)->first();
                if (!$estado) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "Estado '$estadoExcel' no encontrado en la base de datos."
                    ];
                    continue;
                }

                // Verificar si el estado requiere responsable y usuario
                $estadosSinResponsableUsuario = ['ADQUIRIDO', 'PREPARACION', 'DISPONIBLE', 'PERDIDO', 'ROBADO', 'PARA BAJA', 'DONADO', 'VENDIDO'];
                if (!in_array($estadoExcel, $estadosSinResponsableUsuario)) {
                    if (!$responsable) {
                        $errores[] = [
                            'fila' => $fila,
                            'motivo' => "Responsable '$responsableUser' no encontrado."
                        ];
                    }
                    if (!$usuario) {
                        $errores[] = [
                            'fila' => $fila,
                            'motivo' => "Usuario '$usuarioUser' no encontrado."
                        ];
                    }
                    if (!$responsable || !$usuario) {
                        continue;
                    }
                }

                if ($activo) {
                    $activo->responsable_de_activo = $responsable ? $responsable->id : null;
                    $activo->usuario_de_activo = $usuario ? $usuario->id : null;
                    $activo->estado = $estado->id;
                    $activo->justificacion_doble_activo = $justificacion ?: null;

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
                    ];
                } else {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "Activo con número de serie '$nroSerie' no encontrado."
                    ];
                }
            }

            DB::commit();
            return view('importar', compact('datos', 'asignaciones', 'errores'))->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}