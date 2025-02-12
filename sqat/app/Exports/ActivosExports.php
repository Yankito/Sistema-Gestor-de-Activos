<?php

namespace App\Exports;

use App\Models\Activo;
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
        $activos = Activo::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'ID', 'Número de Serie', 'Marca', 'Modelo', 'Tipo de Activo', 
            'Estado', 'Usuario de Activo', 'Responsable', 'Precio', 
            'Ubicación', 'Justificación Doble Activo'
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
            $sheet -> getColumnDimension($column)->setAutoSize(true);
            $column++;
        }

        // Datos
        $row = 2;
        foreach ($activos as $activo) {
            $sheet->setCellValue('A' . $row, $activo->id)
                  ->setCellValue('B' . $row, $activo->nro_serie)
                  ->setCellValue('C' . $row, $activo->marca)
                  ->setCellValue('D' . $row, $activo->modelo)
                  ->setCellValue('E' . $row, $activo->tipo_de_activo)
                  ->setCellValue('F' . $row, $activo->estado)
                  ->setCellValue('G' . $row, $activo->usuario_de_activo)
                  ->setCellValue('H' . $row, $activo->responsable_de_activo)
                  ->setCellValue('I' . $row, $activo->precio)
                  ->setCellValue('J' . $row, $activo->ubicacion)
                  ->setCellValue('K' . $row, $activo->justificacion_doble_activo);

            // Aplicar bordes a las celdas de datos
            $sheet->getStyle('A' . $row . ':K' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            $row++;
        }

        // Estilo de formato de número para columnas con precios
        $sheet->getStyle('I2:I' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);

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