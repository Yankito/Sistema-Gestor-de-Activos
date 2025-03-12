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

class ImportarActivosController extends Controller
{
    use ImportarTrait;  // Usar el trait

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

            // Crear registro en el historial
            $this->crearRegistro('IMPORTÓ ACTIVOS');

            return view('importar.importarActivos', compact('datosGenerales', 'activos', 'errores'))
                ->with('success', 'Importación realizada con éxito.');
        });
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