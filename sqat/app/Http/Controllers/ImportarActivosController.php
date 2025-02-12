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
        return view('/importarActivos');
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

            foreach ($datos as $index => $fila) {
                if ($index == 1) continue; // Saltar encabezados

                // Verificar si la fila está vacía
                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E']) && empty($fila['F']) && empty($fila['G']) && empty($fila['H'])) {
                    continue;
                }

                // Convertir la ubicación a mayúsculas y eliminar tildes
                $ubicacion = $this->eliminarTildesYMayusculas($fila['F']);
                $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();

                if (!$ubicacionExistente) {
                    throw new \Exception("La ubicación '{$ubicacion}' no existe en la base de datos.");
                }

                $ubicacionId = $ubicacionExistente->id;

                // Obtener el ID del estado
                $estadoNombre = $this->eliminarTildesYMayusculas($fila['E']);
                $estado = DB::table('estados')->where('nombre_estado', $estadoNombre)->first();

                if (!$estado) {
                    throw new \Exception("El estado '{$estadoNombre}' no existe en la base de datos.");
                }

                $estadoId = $estado->id;

                // Crear el activo
                $activo = Activo::create([
                    'nro_serie' => $fila['A'],
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $fila['D'],
                    'estado' => $estadoId,
                    'ubicacion' => $ubicacionId,
                    'precio' => $fila['G'],
                    'justificacion_doble_activo' => $fila['H'] ?? null,
                ]);

                $activos[] = $activo;
            }

            DB::commit();
            return view('importar-activos', compact('datos', 'activos'))->with('success', 'Activos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}
