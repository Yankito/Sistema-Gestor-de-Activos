<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;

class ImportarActivosController extends Controller
{
    public function index()
    {
        // Verificar si el usuario es administrador
        if (!auth()->user()->es_administrador) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        }else{
            return view('importarActivos');
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
            'archivo_excel' => 'required|mimes:xlsx,xls'
        ]);

        $archivo = $request->file('archivo_excel');
        $spreadsheet = IOFactory::load($archivo->getPathname());
        $hoja = $spreadsheet->getActiveSheet();
        $datos = $hoja->toArray(null, true, true, true);

        DB::beginTransaction();
        try {
            $activos = [];
            $errores = [];

            foreach ($datos as $index => $fila) {
                if ($index == 1) continue; // Saltar encabezados

                // Verificar si la fila está vacía
                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E'])) {
                    continue;
                }

                // Convertir la ubicación a mayúsculas y eliminar tildes
                $ubicacion = $this->eliminarTildesYMayusculas($fila['E']);
                $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();

                if (empty($fila['E'])) {
                    $ubicacion = 'ROSARIO NORTE';
                    $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();
                }

                if (!$ubicacionExistente) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "La ubicación '{$ubicacion}' no existe en la base de datos."
                    ];
                    continue;
                }

                $ubicacionId = $ubicacionExistente->id;

                // Obtener el ID del estado
                $estadoNombre = 'Adquirido'; // Estado predefinido como 'Adquirido'
                $estado = DB::table('estados')->where('nombre_estado', $estadoNombre)->first();
                $estadoId = $estado->id;

                if(Activo::where('nro_serie', $fila['A'])->exists()) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "El activo con número de serie '{$fila['A']}' ya existe en la base de datos."
                    ];
                    continue;
                }
                //TIPO DE ACTIVO
                $tipoActivo = $this->eliminarTildesYMayusculas($fila['D']);
                $tipoActivoExistente = DB::table('tipo_activo')->where('nombre', $tipoActivo)->first();
                if (!$tipoActivoExistente) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "El tipo de activo '{$tipoActivo}' no existe en la base de datos."
                    ];
                    continue;
                }
                $tipoActivoId = $tipoActivoExistente->id;


                // Crear el activo
                Activo::create([
                    'nro_serie' => $fila['A'],
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $tipoActivoId,
                    'estado' => $estadoId,
                    'responsable_de_activo' => null,
                    'precio' => null,
                    'ubicacion' => $ubicacionId,
                    'justificacion_doble_activo' => null
                ]);
                $ubicacionNombre = $ubicacionExistente->sitio;
                $estadoNombre = $estado->nombre_estado;
                //nombre del tipo de activo
                $tipoActivoNombre = $tipoActivoExistente->nombre;

                $activos[] = [
                    'nro_serie' => $fila['A'],
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $tipoActivoNombre,
                    'estado' => $estadoNombre,
                    'responsable_de_activo' => null,
                    'precio' => null,
                    'ubicacion' => $ubicacionNombre,
                    'justificacion_doble_activo' => null
                ];
            }

            DB::commit();
            return view('importarActivos', compact('datos', 'activos', 'errores'))->with('success', 'Activos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}
