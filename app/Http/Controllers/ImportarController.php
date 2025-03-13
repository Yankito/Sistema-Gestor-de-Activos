<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Persona;
use App\Models\Activo;
use App\Models\Registro;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Traits\ImportarTrait;
use App\Services\ImportarExcelService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;



class ImportarController extends Controller
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
        return view('importar.importar');
    }

    public function descargarErrores(Request $request)
    {
        $errores = session('errores'); // Obtener los errores de la sesión

        if (empty($errores)) {
            return redirect()->back()->with('error', 'No hay errores para descargar.');
        }

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $sheet->setCellValue('A1', 'Responsable');
        $sheet->setCellValue('B1', 'Usuario Activo');
        $sheet->setCellValue('C1', 'Número de Serie');
        $sheet->setCellValue('D1', 'Estado');
        $sheet->setCellValue('E1', 'Justificación Doble Activo');
        $sheet->setCellValue('F1', 'Motivo del Error');

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

        $sheet->getStyle('A1:F1')->applyFromArray($styleArray);

        // Llenar datos
        $row = 2;
        foreach ($errores as $error) {
            $sheet->setCellValue('A' . $row, $error['fila']['A'] ?? '-');
            $sheet->setCellValue('B' . $row, $error['fila']['B'] ?? '-');
            $sheet->setCellValue('C' . $row, $error['fila']['C'] ?? '-');
            $sheet->setCellValue('D' . $row, $error['fila']['D'] ?? '-');
            $sheet->setCellValue('E' . $row, $error['fila']['E'] ?? '-');
            $sheet->setCellValue('F' . $row, $error['motivo']);
            $row++;
        }

        // Ajustar el tamaño de las columnas automáticamente
        foreach (range('A', 'F') as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Crear el archivo Excel
        $writer = new Xlsx($spreadsheet);
        $fileName = 'errores_importacion.xlsx';

        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $fileName . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }

    public function importExcel(Request $request)
    {
        $this->importarExcelService->validarArchivo($request);

        return $this->importarExcelService->manejarTransaccion(function () use ($request) {
            $spreadsheet = IOFactory::load($request->file('archivo_excel')->getPathname());
            $hoja = $spreadsheet->getActiveSheet();
            $datos = $hoja->toArray(null, true, true, true);

            $asignaciones = [];
            $errores = [];

            foreach ($datos as $index => $fila) {
                if ($index == 1 || $this->filaVacia($fila, 'A', 'E')) {
                    continue;
                }

                $fila['A'] = strtoupper($fila['A']);
                $fila['B'] = strtoupper($fila['B']);
                $fila['C'] = strtoupper($fila['C']);
                $fila['D'] = strtoupper($fila['D']);
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

                // Si el estado es 2 (preparación), establecer responsable y usuarios a null
                if (in_array($activo->estado, [2, 8, 9, 10])) {
                    $activo->responsable_de_activo = null;
                    $activo->usuarioDeActivo()->sync([]);
                }

                $activo->save();

                // Crear o actualizar la asignación en la tabla asignaciones
                if ($usuario) {
                    // Usar sync en lugar de syncWithoutDetaching para evitar recrear asignaciones eliminadas
                    $activo->usuarioDeActivo()->sync([$usuario->id]);
                }

                if (in_array($activo->estado, [2, 8, 9, 10])) {
                    $usuarioActivo = null;
                } else {
                    $usuarioActivo = $usuario ? strtoupper($usuario->user) : null;
                }

                // Evaluar el valor de 'responsable' por separado
                $responsable = $activo->responsable_de_activo ? strtoupper(Persona::find($activo->responsable_de_activo)->user) : null;

                $asignaciones[] = [
                    'responsable' => $responsable,
                    'usuario_activo' => $usuarioActivo,
                    'numero_serie' => $activo->nro_serie,
                    'estado' => $estado->nombre_estado,
                    'justificacion' => $activo->justificacion_doble_activo,
                ];
            }

            // Crear registro en el historial
            $this->crearRegistro('IMPORTÓ ASIGNACIONES');
            //guardar errores en la sesión
            session(['errores' => $errores]);

            return view('importar.importar', compact('datos', 'asignaciones', 'errores'))
                ->with('success', 'Datos importados correctamente.');
        });
    }
}
