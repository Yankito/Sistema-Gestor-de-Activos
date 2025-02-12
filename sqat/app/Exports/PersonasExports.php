<?php
namespace App\Exports;

use App\Models\Persona;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class PersonasExports
{
    public function export($formato)
    {
        $personas = Persona::all();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'ID', 'Nombre de Usuario', 'Nombres', 'Primer Apellido', 
            'Segundo Apellido', 'Supervisor', 'Empresa', 'Estado Empleado', 
            'Centro Costo', 'Denominación', 'Título de Puesto', 
            'Fecha Inicio', 'Usuario TI'
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
        foreach ($personas as $persona) {
            $sheet->setCellValue('A' . $row, $persona->id)
                  ->setCellValue('B' . $row, $persona->nombre_usuario)
                  ->setCellValue('C' . $row, $persona->nombres)
                  ->setCellValue('D' . $row, $persona->primer_apellido)
                  ->setCellValue('E' . $row, $persona->segundo_apellido)
                  ->setCellValue('F' . $row, $persona->supervisor)
                  ->setCellValue('G' . $row, $persona->empresa)
                  ->setCellValue('H' . $row, $persona->estado_empleado)
                  ->setCellValue('I' . $row, $persona->centro_costo)
                  ->setCellValue('J' . $row, $persona->denominacion)
                  ->setCellValue('K' . $row, $persona->titulo_puesto)
                  ->setCellValue('L' . $row, $persona->fecha_inicio)
                  ->setCellValue('M' . $row, $persona->usuario_ti);

            // Aplicar bordes a las celdas de datos
            $sheet->getStyle('A' . $row . ':M' . $row)->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['argb' => 'FF000000'],
                    ],
                ],
            ]);

            
            $row++;
        }

        // Crear el archivo
        $filePath = tempnam(sys_get_temp_dir(), 'personas');
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