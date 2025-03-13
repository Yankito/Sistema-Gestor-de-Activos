<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Registro;
use App\Models\Activo;
use App\Models\TipoActivo;
use Illuminate\Support\Facades\DB;
use App\Traits\ImportarTrait;
use App\Services\ImportarExcelService;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Traits\DescargarErroresTrait;

class ImportarActivosController extends Controller
{
    use ImportarTrait;  // Usar el trait
    use ImportarTrait, DescargarErroresTrait;  // Usar los traits

    protected $importarExcelService;

    public function __construct(ImportarExcelService $importarExcelService)
    {
        $this->importarExcelService = $importarExcelService;
    }

    public function index()
    {
        if ($redirect = $this->redirigirSiNoEsAdmin()) {
            return $redirect;
        }
        return view('importar.importarActivos');
    }

    public function descargarErrores()
    {
        $errores = session('errores', []);
    
        // Definir los encabezados que coinciden con las claves de $error['fila']
        $encabezados = [
            'A' => 'Número de serie',
            'B' => 'Marca',
            'C' => 'Modelo',
            'D' => 'Tipo de activo',
            'E' => 'Ubicación',
        ];
    
        return $this->descargarErroresExcel($errores, $encabezados, 'Errores_Importacion_Activos.xlsx');
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
        $tiposActivos = TipoActivo::with('caracteristicasAdicionales')->get();

        foreach ($tiposActivos as $tipoActivo) {
            if ($tipoActivo->caracteristicasAdicionales->isEmpty()) {
                continue;
            }
            $hoja = $spreadsheet->createSheet();
            $hoja->setTitle($tipoActivo->nombre);

            // Cabecera por defecto
            $hoja->setCellValue('A1', 'Número de serie');

            // Consultar las características adicionales para el tipo de activo
            $caracteristicas = $tipoActivo->caracteristicasAdicionales->pluck('nombre_caracteristica')->toArray();

            // Asignar cabeceras adicionales
            $columna = 'B';
            foreach ($caracteristicas as $caracteristica) {
                $hoja->setCellValue($columna . '1', $caracteristica);
                $columna++;
            }
            $columna = chr(ord($columna) - 1);
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
        $this->importarExcelService->validarArchivo($request);

        return $this->importarExcelService->manejarTransaccion(function () use ($request) {
            $spreadsheet = IOFactory::load($request->file('archivo_excel')->getPathname());
            $hojaGeneral = $spreadsheet->getSheetByName('General');

            if (!$hojaGeneral) {
                throw new \Exception('La hoja General no fue encontrada en el archivo.');
            }

            $datosGenerales = $hojaGeneral->toArray(null, true, true, true);

            $activos = [];
            $errores = [];

            foreach ($datosGenerales as $index => $fila) {
                if ($index == 1 || $this->filaVacia($fila, 'A', 'E')) {
                    continue; // Saltar encabezados y filas vacías
                }

                $tipoActivoNombre = $this->eliminarTildesYMayusculas($fila['D']);
                $tipoActivo = DB::table('tipo_activo')->where('nombre', $tipoActivoNombre)->first();

                if (!$tipoActivo) {
                    $errores[] = [
                        'fila' => [
                            'A' => $fila['A'] ?? '-', // Responsable
                            'B' => $fila['B'] ?? '-', // Usuario Activo
                            'C' => $fila['C'] ?? '-', // Número de Serie
                            'D' => $fila['D'] ?? '-', // Estado
                            'E' => $fila['E'] ?? '-', // Justificación Doble Activo
                        ], 
                        'motivo' => "El tipo de activo '{$tipoActivoNombre}' no existe en la base de datos."];
                    continue;
                }

                $ubicacion = $this->eliminarTildesYMayusculas($fila['E']);
                if (empty($fila['E'])) {
                    $ubicacion = 'ROSARIO NORTE';
                }

                $ubicacionExistente = DB::table('ubicaciones')->where('sitio', $ubicacion)->first();

                if (!$ubicacionExistente) {
                    $errores[] = [
                        'fila' => [
                            'A' => $fila['A'] ?? '-', // Responsable
                            'B' => $fila['B'] ?? '-', // Usuario Activo
                            'C' => $fila['C'] ?? '-', // Número de Serie
                            'D' => $fila['D'] ?? '-', // Estado
                            'E' => $fila['E'] ?? '-', // Justificación Doble Activo
                        ], 
                        'motivo' => "La ubicación '{$ubicacion}' no existe en la base de datos."];
                    continue;
                }

                $estado = DB::table('estados')->where('nombre_estado', 'Adquirido')->first();

                if (Activo::where('nro_serie', strtoupper($fila['A']))->exists()) {
                    $errores[] = [
                        'fila' => [
                            'A' => $fila['A'] ?? '-', // Responsable
                            'B' => $fila['B'] ?? '-', // Usuario Activo
                            'C' => $fila['C'] ?? '-', // Número de Serie
                            'D' => $fila['D'] ?? '-', // Estado
                            'E' => $fila['E'] ?? '-', // Justificación Doble Activo
                        ], 
                        'motivo' => "El activo con número de serie '{$fila['A']}' ya existe en la base de datos."];
                    continue;
                }

                $nuevoActivo = Activo::create([
                    'nro_serie' => strtoupper($fila['A']),
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $tipoActivo->id,
                    'estado' => $estado->id,
                    'responsable_de_activo' => null,
                    'precio' => null,
                    'ubicacion' => $ubicacionExistente->id,
                    'justificacion_doble_activo' => null
                ]);

                $caracteristicasAdicionales = $this->agregarCaracteristicasAdicionales($spreadsheet, $tipoActivo, $fila['A'], $nuevoActivo);

                $activos[] = [
                    'nro_serie' => $fila['A'],
                    'marca' => $fila['B'],
                    'modelo' => $fila['C'],
                    'tipo_de_activo' => $tipoActivo->nombre,
                    'estado' => $estado->nombre_estado,
                    'responsable_de_activo' => null,
                    'precio' => null,
                    'ubicacion' => $ubicacionExistente->sitio,
                    'justificacion_doble_activo' => null,
                    'caracteristicas_adicionales' => $caracteristicasAdicionales
                ];
            }
            // Almacenar errores en la sesión para su descarga
            session(['errores' => $errores]);

            // Crear registro en el historial
            $this->crearRegistro('IMPORTÓ ACTIVOS');

            return view('importar.importarActivos', compact('datosGenerales', 'activos', 'errores'))
                ->with('success', 'Importación realizada con éxito.');
        });
    }

    public function agregarCaracteristicasAdicionales($spreadsheet, $tipoActivo, $nroSerie, $nuevoActivo){
        // Inicializar el array de características adicionales
        $caracteristicasAdicionales = [];

        $hojaEspecifica = $spreadsheet->getSheetByName($tipoActivo->nombre);

        if ($hojaEspecifica) {
            $datosEspecificos = $hojaEspecifica->toArray(null, true, true, true);

            foreach ($datosEspecificos as $idx => $filaEspecifica) {
                if ($idx == 1 || $filaEspecifica['A'] !== $nroSerie){
                    continue;
                }

                $caracteristicas = DB::table('caracteristicas_adicionales')
                    ->where('tipo_activo_id', $tipoActivo->id)
                    ->pluck('id', 'nombre_caracteristica');

                $columna = 'B';
                foreach ($caracteristicas as $nombre => $caracteristicaId) {
                    $valor = $filaEspecifica[$columna] ?? null;

                    if ($valor) {
                        DB::table('valores_adicionales')->insert([
                            'id_activo' => $nuevoActivo->id,
                            'id_caracteristica' => $caracteristicaId,
                            'valor' => $valor
                        ]);

                        // Agregar las características adicionales al array
                        $caracteristicasAdicionales[] = [
                            'nombre' => $nombre,
                            'valor' => $valor
                        ];
                    }
                    $columna++;
                }
            }
        }
        return $caracteristicasAdicionales;
    }

}
