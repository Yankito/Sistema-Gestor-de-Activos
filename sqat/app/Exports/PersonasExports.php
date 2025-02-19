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
            'User','Rut', 'Nombre Completo', 'Nombre Empresa', 
            'Estado', 'Fecha de Inicio', 'Fecha Término', 'Cargo', 
            'Ubicación', 'Correo'
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
            $sheet->setCellValue('A' . $row, $persona->user)
                  ->setCellValue('B' . $row, $persona->rut)
                  ->setCellValue('C' . $row, $persona->nombre_completo)
                  ->setCellValue('D' . $row, $persona->nombre_empresa)
                  ->setCellValue('E' . $row, $persona->estado_empleado)
                  ->setCellValue('F' . $row, $persona->fecha_ing)
                  ->setCellValue('G' . $row, $persona->fecha_ter)
                  ->setCellValue('H' . $row, $persona->cargo)
                  ->setCellValue('I' . $row, $persona->ubicacion)
                  ->setCellValue('J' . $row, $persona->correo);
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