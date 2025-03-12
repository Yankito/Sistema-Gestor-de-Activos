<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;
use App\Models\Activo;
use App\Models\TipoActivo;
use Illuminate\Support\Facades\DB;

class ImportarActivosController extends Controller
{
    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls|max:5120',
        ], [
            'archivo_excel.max' => 'El archivo no debe ser mayor a 5 MB.',
        ]);

        $archivo = $request->file('archivo_excel');
        $spreadsheet = IOFactory::load($archivo->getPathname());
        $hojaGeneral = $spreadsheet->getSheetByName('General');

        if (!$hojaGeneral) {
            return back()->with('error', 'La hoja General no fue encontrada en el archivo.');
        }

        $datosGenerales = $hojaGeneral->toArray(null, true, true, true);

        DB::beginTransaction();
        try {
            $activos = [];
            $errores = [];

            foreach ($datosGenerales as $index => $fila) {
                if ($index == 1 || $this->filaVacia($fila)) continue;

                $tipoActivo = $this->validarTipoActivo($fila['D']);
                if (!$tipoActivo) {
                    $errores[] = $this->errorFila($fila, "El tipo de activo '{$fila['D']}' no existe en la base de datos.");
                    continue;
                }

                $ubicacion = $this->validarUbicacion($fila['E']);
                if (!$ubicacion) {
                    $errores[] = $this->errorFila($fila, "La ubicación '{$fila['E']}' no existe en la base de datos.");
                    continue;
                }

                if (Activo::where('nro_serie', strtoupper($fila['A']))->exists()) {
                    $errores[] = $this->errorFila($fila, "El activo con número de serie '{$fila['A']}' ya existe en la base de datos.");
                    continue;
                }

                $nuevo_activo = $this->crearActivo($fila, $tipoActivo, $ubicacion);
                $caracteristicasAdicionales = $this->importarCaracteristicas($spreadsheet, $tipoActivo, $nuevo_activo, $fila['A']);

                $activos[] = $this->formatoActivo($fila, $tipoActivo, $ubicacion, $caracteristicasAdicionales);
            }

            $this->registrarHistorial();

            DB::commit();

            return view('importar.importarActivos', compact('datosGenerales', 'activos', 'errores'))
                ->with('success', 'Importación realizada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error durante la importación: ' . $e->getMessage());
        }
    }

    private function filaVacia($fila)
    {
        return empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E']);
    }

    private function validarTipoActivo($nombre)
    {
        return DB::table('tipo_activo')->where('nombre', $this->eliminarTildesYMayusculas($nombre))->first();
    }

    private function validarUbicacion($nombre)
    {
        $ubicacion = $this->eliminarTildesYMayusculas($nombre ?: 'ROSARIO NORTE');
        return DB::table('ubicaciones')->where('sitio', $ubicacion)->first();
    }

    private function errorFila($fila, $motivo)
    {
        return ['fila' => $fila, 'motivo' => $motivo];
    }

    private function crearActivo($fila, $tipoActivo, $ubicacion)
    {
        $estado = DB::table('estados')->where('nombre_estado', 'Adquirido')->first();

        return Activo::create([
            'nro_serie' => strtoupper($fila['A']),
            'marca' => $fila['B'],
            'modelo' => $fila['C'],
            'tipo_de_activo' => $tipoActivo->id,
            'estado' => $estado->id,
            'ubicacion' => $ubicacion->id,
            'responsable_de_activo' => null,
            'precio' => null,
            'justificacion_doble_activo' => null
        ]);
    }

    private function importarCaracteristicas($spreadsheet, $tipoActivo, $activo, $nroSerie)
    {
        $caracteristicasAdicionales = [];
        $hojaEspecifica = $spreadsheet->getSheetByName($tipoActivo->nombre);

        if (!$hojaEspecifica) return $caracteristicasAdicionales;

        $datosEspecificos = $hojaEspecifica->toArray(null, true, true, true);
        $caracteristicas = DB::table('caracteristicas_adicionales')
            ->where('tipo_activo_id', $tipoActivo->id)
            ->pluck('id', 'nombre_caracteristica');

        foreach ($datosEspecificos as $idx => $filaEspecifica) {
            if ($idx == 1 || $filaEspecifica['A'] !== $nroSerie) continue;

            $columna = 'B';
            foreach ($caracteristicas as $nombre => $caracteristicaId) {
                if (!isset($filaEspecifica[$columna])) break;

                DB::table('valores_adicionales')->insert([
                    'id_activo' => $activo->id,
                    'id_caracteristica' => $caracteristicaId,
                    'valor' => $filaEspecifica[$columna]
                ]);

                $caracteristicasAdicionales[] = ['nombre' => $nombre, 'valor' => $filaEspecifica[$columna]];
                $columna++;
            }
        }

        return $caracteristicasAdicionales;
    }

    private function formatoActivo($fila, $tipoActivo, $ubicacion, $caracteristicasAdicionales)
    {
        return [
            'nro_serie' => $fila['A'],
            'marca' => $fila['B'],
            'modelo' => $fila['C'],
            'tipo_de_activo' => $tipoActivo->nombre,
            'estado' => 'Adquirido',
            'ubicacion' => $ubicacion->sitio,
            'caracteristicas_adicionales' => $caracteristicasAdicionales
        ];
    }

    private function registrarHistorial()
    {
        Registro::create([
            'activo' => null,
            'persona' => null,
            'tipo_cambio' => 'IMPORTÓ ACTIVOS',
            'encargado_cambio' => Auth::user()->id
        ]);
    }

    private function eliminarTildesYMayusculas($cadena)
    {
        $buscar = ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ñ'];
        $reemplazar = ['A', 'E', 'I', 'O', 'U', 'N'];
        return str_replace($buscar, $reemplazar, strtoupper($cadena));
    }
}
