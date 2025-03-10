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
                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E']) && empty($fila['F'])) {
                        continue;
                    }
                //obetener tipo de activo 
                $tipoActivoNombre = $this->eliminarTildesYMayusculas($fila['D']);
                $tipoActivo = DB::table('tipo_activo')->where('nombre', $tipoActivoNombre)->first();

                if (!$tipoActivo) {
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "El tipo de activo '{$tipoActivo}' no existe en la base de datos."
                    ];
                    continue;
                }

                //obtener las caracteristicas permitidas para este tipo de activo
                $caracteristicasPermitidas = DB::table('caracteristicas_adicionales')
                ->where('tipo_activo_id', $tipoActivo->id)
                ->get()
                ->keyBy('nombre_caracteristica');

                //processar las caracteristicas adicionales

                $caracteristicasIngresadas =!empty($fila['F']) ? explode("\n", $fila['F']) : [];
                $caracteristicasValidas = [];
                $caracteristicasInvalidas = [];

                foreach ($caracteristicasIngresadas as $caracteristica) {
                    $caracteristica = trim($caracteristica); // Eliminar espacios en blanco
                    if (empty($caracteristica)) {
                        continue; // Saltar si la línea está vacía
                    }
                
                    // Dividir el nombre y el valor de la característica
                    $partes = explode(':', $caracteristica, 2); // Dividir en máximo 2 partes
                    $nombreCaracteristica = strtoupper(trim($partes[0] ?? '')); // Normalizar nombre
                    $valorCaracteristica = trim($partes[1] ?? ''); // Valor de la característica
                
                    // Verificar si la característica es permitida
                    if (isset($caracteristicasPermitidas[$nombreCaracteristica])) {
                        $caracteristicaId = $caracteristicasPermitidas[$nombreCaracteristica]->id; // Obtener el ID de la base de datos
                        $caracteristicasValidas[] = "{$nombreCaracteristica}: {$valorCaracteristica}";
                    } else {
                        $caracteristicasInvalidas[] = $caracteristica;
                    }
                }

                if(!empty($caracteristicasInvalidas)){
                    $errores[] = [
                        'fila' => $fila,
                        'motivo' => "Características no permitidas para '{$tipoActivoNombre}': " . implode(', ', $caracteristicasInvalidas)
                    ];
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
                $activo = Activo::create([
                    'nro_serie' => $fila['A'],
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $tipoActivoId,
                    'estado' => $estadoId,
                    'responsable_de_activo' => null,
                    'precio' => null,
                    'ubicacion' => $ubicacionId,
                    'justificacion_doble_activo' => null,
                    'caracteristicas_adicionales' => !empty($caracteristicasValidas) ? implode(',', $caracteristicasValidas) :null
                ]);

                //guardar los valores adicionales en la tabla valores_adicionales
                if (!empty($caracteristicasValidas)) {
                    foreach ($caracteristicasValidas as $caracteristicaValida) {
                        // Descomponer el string en nombre y valor
                        list($nombreCaracteristica, $valorCaracteristica) = explode(':', $caracteristicaValida);

                        // Obtener el ID de la característica adicional
                        $caracteristicaId = $caracteristicasPermitidas[$nombreCaracteristica]->id;

                        // Insertar en la tabla valores_adicionales
                        DB::table('valores_adicionales')->insert([
                            'id_activo' => $activo->id,
                            'id_caracteristica' => $caracteristicaId,
                            'valor' => trim($valorCaracteristica)
                        ]);
                    }
                }
            

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
                    'justificacion_doble_activo' => null,
                    'caracteristicas_adicionales' => $caracteristicasValidas
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
