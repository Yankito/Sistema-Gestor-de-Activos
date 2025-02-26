<?php
namespace App\Exports;

use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Alignment;

class ActivosPersonasExports
{
    public function export($formato)
    {
        $data = DB::select("
            SELECT 
                activos.nro_serie,
                activos.marca,
                activos.modelo,
                activos.tipo_de_activo,
                estados.nombre_estado AS estado_activo,
                activos.usuario_de_activo,
                activos.responsable_de_activo,
                activos.precio,
                ubicaciones.sitio AS nombre_ubicacion,
                activos.justificacion_doble_activo,
                personas.user,
                personas.rut,
                personas.nombre_completo,
                personas.nombre_empresa,
                personas.estado_empleado,
                personas.fecha_ing,
                personas.fecha_ter,
                personas.cargo,
                ubicaciones.sitio AS ubicacion_persona,
                personas.correo
                
            FROM 
                activos
            JOIN 
                personas ON activos.responsable_de_activo = personas.id
            JOIN 
                ubicaciones ON activos.ubicacion = ubicaciones.id
            JOIN    
                estados ON activos.estado = estados.id"
        );

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'Número de Serie', 'Marca', 'Modelo', 'Tipo de Activo', 
            'Estado Activo', 'Usuario de Activo', 'Responsable de Activo', 'Precio', 
            'Ubicación', 'Justificación Doble Activo', 'Usuario', 'RUT', 'Nombre Completo','Empresa', 
            'Estado Empleado','Fecha Ingreso', 'Fecha Termino', 'Cargo', 
            'Ubicación Persona', 'correo'
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
        foreach ($data as $item) {
            $sheet->setCellValue('A' . $row, $item->nro_serie)
                  ->setCellValue('B' . $row, $item->marca)
                  ->setCellValue('C' . $row, $item->modelo)
                  ->setCellValue('D' . $row, $item->tipo_de_activo)
                  ->setCellValue('E' . $row, $item->estado_activo)
                  ->setCellValue('F' . $row, $item->usuario_de_activo)
                  ->setCellValue('G' . $row, $item->responsable_de_activo)
                  ->setCellValue('H' . $row, $item->precio)
                  ->setCellValue('I' . $row, $item->nombre_ubicacion)
                  ->setCellValue('J' . $row, $item->justificacion_doble_activo)
                  ->setCellValue('K' . $row, $item->user)
                  ->setCellValue('L' . $row, $item->rut)
                  ->setCellValue('M' . $row, $item->nombre_completo)
                  ->setCellValue('N' . $row, $item->nombre_empresa)
                  ->setCellValue('O' . $row, $item->estado_empleado)
                  ->setCellValue('P' . $row, $item->fecha_ing)
                  ->setCellValue('Q' . $row, $item->fecha_ter)
                  ->setCellValue('R' . $row, $item->cargo)
                  ->setCellValue('S' . $row, $item->ubicacion_persona)
                  ->setCellValue('T' . $row, $item->correo);

            // Aplicar bordes a las celdas de datos
            $sheet->getStyle('A' . $row . ':X' . $row)->applyFromArray([
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
        $filePath = tempnam(sys_get_temp_dir(), 'activos_personas');
        if ($formato == 'excel') {
            $writer = new Xlsx($spreadsheet);
            $filePath .= '.xlsx';
        } elseif ($formato == 'csv') {
            $writer = new Csv($spreadsheet);
            $filePath .= '.csv';
        } elseif ($formato == 'pdf') {
            $writer = new Mpdf($spreadsheet);
            $filePath .= '.pdf';
        }
        $writer->save($filePath);

        return $filePath;
    }
}