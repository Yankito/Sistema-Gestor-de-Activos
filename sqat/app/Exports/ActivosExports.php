<?php

namespace App\Exports;

use App\Models\Activo;
use App\Models\TipoActivo;
use App\Models\Estado;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Asignacion; // Importar el modelo Asignacion
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class ActivosExports
{
    public function export($formato)
    {
        // Obtener todos los activos
        $activos = Activo::all();

        // Obtener los datos de las tablas relacionadas
        $tiposActivo = TipoActivo::pluck('nombre', 'id')->all();
        $estados = Estado::pluck('nombre_estado', 'id')->all();
        $responsables = Persona::pluck('nombre_completo', 'id')->all();
        $ubicaciones = Ubicacion::pluck('sitio', 'id')->all();
        $personas = Persona::pluck('nombre_completo', 'id')->all(); // Para obtener nombres de usuarios

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'Número de Serie', 'Marca', 'Modelo', 'Tipo de Activo', 'Estado', 'Usuario de Activo', 'Responsable de Activo', 'Precio', 'Ubicación', 'Justificación Doble Activo',
        ];

        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $sheet->getStyle($column . '1')->applyFromArray([
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4CAF50'],
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }

        // Datos
        $row = 2;
        foreach ($activos as $activo) {
            // Obtener los nombres correspondientes a los IDs
            $tipoActivoNombre = $tiposActivo[$activo->tipo_de_activo] ?? 'Desconocido';
            $estadoNombre = $estados[$activo->estado] ?? 'Desconocido';
            $responsableNombre = $responsables[$activo->responsable_de_activo] ?? 'Desconocido';
            $ubicacionNombre = $ubicaciones[$activo->ubicacion] ?? 'Desconocido';

            // Obtener los usuarios asignados al activo actual
            $usuariosAsignados = Asignacion::where('id_activo', $activo->id)
                ->join('personas', 'asignaciones.id_persona', '=', 'personas.id')
                ->pluck('personas.nombre_completo')
                ->toArray();

            // Formatear los nombres de los usuarios
            $usuariosFormateados = implode("\n• ", $usuariosAsignados);
            if (!empty($usuariosFormateados)) {
                $usuariosFormateados = "• " . $usuariosFormateados;
            }

            $sheet->setCellValue('A' . $row, $activo->nro_serie)
                  ->setCellValue('B' . $row, $activo->marca)
                  ->setCellValue('C' . $row, $activo->modelo)
                  ->setCellValue('D' . $row, $tipoActivoNombre) // Tipo de Activo (nombre)
                  ->setCellValue('E' . $row, $estadoNombre) // Estado (nombre)
                  ->setCellValue('F' . $row, $usuariosFormateados) // Usuarios de Activo (formateados)
                  ->setCellValue('G' . $row, $responsableNombre) // Responsable de Activo (nombre)
                  ->setCellValue('H' . $row, $activo->precio)
                  ->setCellValue('I' . $row, $ubicacionNombre) // Ubicación (nombre)
                  ->setCellValue('J' . $row, $activo->justificacion_doble_activo);

            // Aplicar bordes a las celdas de datos
            $sheet->getStyle('A' . $row . ':J' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            // Ajustar el alto de la fila si hay múltiples usuarios
            if (count($usuariosAsignados) > 1) {
                $sheet->getRowDimension($row)->setRowHeight(15 * count($usuariosAsignados));
            }

            $row++;
        }

        // Estilo de formato de número para columnas con precios
        $sheet->getStyle('H2:H' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

        // Crear el archivo
        $filePath = tempnam(sys_get_temp_dir(), 'activos');
        if ($formato == 'excel') {
            $writer = new Xlsx($spreadsheet);
            $filePath .= '.xlsx';
        } elseif ($formato == 'csv') {
            $writer = new Csv($spreadsheet);
            $filePath .= '.csv';
        } elseif ($formato == 'pdf') {
            // Implementar la lógica para exportar a PDF si es necesario
        }
        $writer->save($filePath);

        return $filePath;
    }
}