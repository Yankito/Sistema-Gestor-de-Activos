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
                    'responsable' => $activo->responsable_de_activo ? Persona::find($activo->responsable_de_activo)->user : null,
                    'usuario_activo' => $usuario ? $usuario->user : null,
                    'numero_serie' => $activo->nro_serie,
                    'estado' => $estado->nombre_estado,
                    'justificacion' => $activo->justificacion_doble_activo,
                ];
            }
    
            DB::commit();
            return view('importar', compact('datos', 'asignaciones', 'errores'))->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}