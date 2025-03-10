<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Activo;
use Illuminate\Support\Facades\DB;

class ImportarActivosController extends Controller
{
    public function index()
    {
        if (!auth()->user()->es_administrador) {
            return redirect('/dashboard')->with('error', 'No tienes permisos para acceder a esta página.');
        } else {
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

    public function generarPlantilla()
    {
        $spreadsheet = new Spreadsheet();

        // Hoja principal general
        $hojaGeneral = $spreadsheet->getActiveSheet();
        $hojaGeneral->setTitle('General');
        $hojaGeneral->setCellValue('A1', 'Número de serie');
        $hojaGeneral->setCellValue('B1', 'Marca');
        $hojaGeneral->setCellValue('C1', 'Modelo');
        $hojaGeneral->setCellValue('D1', 'Tipo de activo');
        $hojaGeneral->setCellValue('E1', 'Ubicación');

        // Estilo para las cabeceras
        $styleArray = [
            'font' => [
                'bold' => true,
                'color' => ['argb' => 'FFFFFFFF'],
            ],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['argb' => 'FF808080'],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $hojaGeneral->getStyle('A1:E1')->applyFromArray($styleArray);

        // Ajustar ancho de columnas
        foreach (range('A', 'E') as $columnID) {
            $hojaGeneral->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Consultar los tipos de activos
        $tiposActivos = DB::table('tipo_activo')->get();

        foreach ($tiposActivos as $tipoActivo) {
            $hoja = $spreadsheet->createSheet();
            $hoja->setTitle($tipoActivo->nombre);

            // Cabecera por defecto
            $hoja->setCellValue('A1', 'Número de serie');

            // Consultar las características adicionales para el tipo de activo
            $caracteristicas = DB::table('caracteristicas_adicionales')
                ->where('tipo_activo_id', $tipoActivo->id)
                ->pluck('nombre_caracteristica');

            // Asignar cabeceras adicionales
            $columna = 'B';
            foreach ($caracteristicas as $caracteristica) {
                $hoja->setCellValue($columna . '1', $caracteristica);
                $columna++;
            }

            // Aplicar estilo a las cabeceras
            $hoja->getStyle('A1:' . $columna . '1')->applyFromArray($styleArray);

            // Ajustar ancho de columnas
            foreach (range('A', $columna) as $columnID) {
                $hoja->getColumnDimension($columnID)->setAutoSize(true);
            }
        }

        // Crear archivo Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Plantilla_Importacion_Activos.xlsx';
        $filePath = storage_path('app/public/' . $fileName);

        $writer->save($filePath);

        return response()->download($filePath)->deleteFileAfterSend(true);
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls'
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
                if ($index == 1) continue;

                if (empty($fila['A']) && empty($fila['B']) && empty($fila['C']) && empty($fila['D']) && empty($fila['E'])) {
                    continue;
                }

                $tipoActivoNombre = $this->eliminarTildesYMayusculas($fila['D']);
                $tipoActivo = DB::table('tipo_activo')->where('nombre', $tipoActivoNombre)->first();

                if (!$tipoActivo) {
                    $errores[] = ['fila' => $fila, 'motivo' => "El tipo de activo '{$tipoActivoNombre}' no existe en la base de datos."];
                    continue;
                }

                $ubicacion = $this->eliminarTildesYMayusculas($fila['E']);
                if (empty($fila['E'])) {
                    $ubicacion = 'ROSARIO NORTE';
                }

                $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();

                if (!$ubicacionExistente) {
                    $errores[] = ['fila' => $fila, 'motivo' => "La ubicación '{$ubicacion}' no existe en la base de datos."];
                    continue;
                }

                $estado = DB::table('estados')->where('nombre_estado', 'Adquirido')->first();

                if (Activo::where('nro_serie', $fila['A'])->exists()) {
                    $errores[] = ['fila' => $fila, 'motivo' => "El activo con número de serie '{$fila['A']}' ya existe en la base de datos."];
                    continue;
                }

                $activo = Activo::create([
                    'nro_serie' => $fila['A'],
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $tipoActivo->id,
                    'estado' => $estado->id,
                    'responsable_de_activo' => null,
                    'precio' => null,
                    'ubicacion' => $ubicacionExistente->id,
                    'justificacion_doble_activo' => null
                ]);

                $hojaEspecifica = $spreadsheet->getSheetByName($tipoActivo->nombre);

                if ($hojaEspecifica) {
                    $datosEspecificos = $hojaEspecifica->toArray(null, true, true, true);

                    foreach ($datosEspecificos as $idx => $filaEspecifica) {
                        if ($idx == 1) continue;

                        if ($filaEspecifica['A'] !== $fila['A']) continue;

                        $caracteristicas = DB::table('caracteristicas_adicionales')
                            ->where('tipo_activo_id', $tipoActivo->id)
                            ->pluck('id', 'nombre_caracteristica');

                        $columna = 'B';
                        foreach ($caracteristicas as $nombre => $caracteristicaId) {
                            $valor = $filaEspecifica[$columna] ?? null;

                            if ($valor) {
                                DB::table('valores_adicionales')->insert([
                                    'id_activo' => $activo->id,
                                    'id_caracteristica' => $caracteristicaId,
                                    'valor' => $valor
                                ]);
                            }
                            $columna++;
                        }
                    }
                }

                $activos[] = $activo;
            }

            DB::commit();

            if (!empty($errores)) {
                return back()->with('errores', $errores);
            }

            return back()->with('success', 'Importación completada con éxito.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Error durante la importación: ' . $e->getMessage());
        }
    }
}
