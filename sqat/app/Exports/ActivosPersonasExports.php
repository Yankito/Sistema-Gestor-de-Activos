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
                activos.id AS id_activo,
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
                personas.nombre_usuario,
                personas.nombres,
                personas.primer_apellido,
                personas.segundo_apellido,
                personas.supervisor,
                personas.empresa,
                personas.estado_empleado,
                personas.centro_costo,
                personas.denominacion,
                personas.titulo_puesto,
                personas.fecha_inicio,
                personas.usuario_ti,
                ubicaciones.sitio AS ubicacion_persona
            FROM 
                activos
            JOIN 
                personas ON activos.responsable_de_activo = personas.id
            JOIN 
                ubicaciones ON activos.ubicacion = ubicaciones.id
            JOIN    
                estados ON activos.estado = estados.id
        ");

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $headers = [
            'ID Activo', 'Nro Serie', 'Marca', 'Modelo', 'Tipo de Activo', 'Estado', 
            'Usuario de Activo', 'Responsable', 'Precio', 'Ubicación', 
            'Justificación Doble Activo', 'Nombre Usuario', 'Nombres', 
            'Primer Apellido', 'Segundo Apellido', 'Supervisor', 'Empresa', 
            'Estado Empleado', 'Centro Costo', 'Denominación', 'Título de Puesto', 
            'Fecha Inicio', 'Usuario TI', 'Ubicación Persona'
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
            $sheet->setCellValue('A' . $row, $item->id_activo)
                  ->setCellValue('B' . $row, $item->nro_serie)
                  ->setCellValue('C' . $row, $item->marca)
                  ->setCellValue('D' . $row, $item->modelo)
                  ->setCellValue('E' . $row, $item->tipo_de_activo)
                  ->setCellValue('F' . $row, $item->estado_activo)
                  ->setCellValue('G' . $row, $item->usuario_de_activo)
                  ->setCellValue('H' . $row, $item->responsable_de_activo)
                  ->setCellValue('I' . $row, $item->precio)
                  ->setCellValue('J' . $row, $item->nombre_ubicacion)
                  ->setCellValue('K' . $row, $item->justificacion_doble_activo)
                  ->setCellValue('L' . $row, $item->nombre_usuario)
                  ->setCellValue('M' . $row, $item->nombres)
                  ->setCellValue('N' . $row, $item->primer_apellido)
                  ->setCellValue('O' . $row, $item->segundo_apellido)
                  ->setCellValue('P' . $row, $item->supervisor)
                  ->setCellValue('Q' . $row, $item->empresa)
                  ->setCellValue('R' . $row, $item->estado_empleado)
                  ->setCellValue('S' . $row, $item->centro_costo)
                  ->setCellValue('T' . $row, $item->denominacion)
                  ->setCellValue('U' . $row, $item->titulo_puesto)
                  ->setCellValue('V' . $row, $item->fecha_inicio)
                  ->setCellValue('W' . $row, $item->usuario_ti)
                  ->setCellValue('X' . $row, $item->ubicacion_persona);

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