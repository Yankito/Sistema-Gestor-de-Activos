<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Activo;
use App\Models\Ubicacion;
use App\Models\Persona;
use Illuminate\Support\Facades\DB;



class ImportarController extends Controller
{
    public function index()
    {
        // Lógica para la vista o los datos que quieras mostrar
        return view('/importar');
    }
    public function previewExcel(Request $request){
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);
        $path = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, false);
        $datawithoutheader = array_slice($data, 1);
        dd($datawithoutheader);
        return view('importar', compact('dataWithoutHeader'));

    }

    public function importExcel(Request $request){
        $data = json_decode($request->input('data'), true);

        if(!$data || empty($data)){
            return redirect()->back()->with('error', 'No hay datos para importar.');
        }
        DB::beginTransaction();
        try {
            foreach ($dataWithoutHeader as $row) {
                if (count($row) < 21) continue; // Evita filas incompletas excepto justificacion_doble_activo

                $estadoEmpleado = strtolower(trim($row[7])) == 'activo' ? 1 : 0;
                $usuarioTI = strtolower(trim($row[12])) == 'si' ? 1 : 0;
                $fechaInicio = date('Y-m-d', strtotime(str_replace(['/','-'], '-', $row[11])));
                $estado = strtoupper(trim($row[16]));
                $ubicacion = Ubicacion::whereRaw("LOWER(nombre) = ?", [strtolower(trim($row[20]))])->first();
                if (!$ubicacion) continue; // Si la ubicación no existe, salta la fila

                // Insertar o actualizar personas
                Persona::updateOrCreate(
                    ['rut' => trim($row[0])],
                    [
                        'nombre_usuario' => trim($row[1]),
                        'nombres' => trim($row[2]),
                        'primer_apellido' => trim($row[3]),
                        'segundo_apellido' => trim($row[4]),
                        'supervisor' => trim($row[5]),
                        'empresa' => trim($row[6]),
                        'estado_empleado' => $estadoEmpleado,
                        'centro_costo' => trim($row[8]),
                        'denominacion' => trim($row[9]),
                        'titulo_puesto' => trim($row[10]),
                        'fecha_inicio' => $fechaInicio,
                        'usuario_ti' => $usuarioTI,
                        'ubicacion' => $ubicacion->id
                    ]
                );

                // Insertar o actualizar activos
                Activo::updateOrCreate(
                    ['nroSerie' => trim($row[13])],
                    [
                        'marca' => trim($row[14]),
                        'modelo' => trim($row[15]),
                        'estado' => $estado,
                        'usuario_de_activo' => trim($row[0]),
                        'responsable_de_activo' => trim($row[17]),
                        'precio' => (int) trim($row[18]),
                        'ubicacion' => $ubicacion->id,
                        'justificacion_doble_activo' => trim($row[19])
                    ]
                );
            }
            DB::commit();
            return redirect()->back()->with('success', 'Datos importados correctamente.');
        } catch (\Exception $e) {
            DB::rollback();
            return redirect()->back()->with('error', 'Error al importar los datos: ' . $e->getMessage());
        }
    }
}
