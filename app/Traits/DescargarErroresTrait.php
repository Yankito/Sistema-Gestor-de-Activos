<?php

namespace App\Traits;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

trait DescargarErroresTrait
{

    private function getHeaderStyle(): array
    {
        return [
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
    }
    /**
     * Descarga un archivo Excel con los errores de importación.
     *
     * @param array $errores
     * @param array $encabezados
     * @param string $nombreArchivo
     * @return StreamedResponse
     */
    public function descargarErroresExcel(array $errores, array $encabezados, string $nombreArchivo): StreamedResponse
    {
        if (empty($errores)) {
            return redirect()->back()->with('error', 'No hay errores para descargar.');
        }

        $spreadsheet = new Spreadsheet();
       
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('General');

        // Establecer encabezados
        $columna = 'A';
        foreach ($encabezados as $encabezado) {
            $sheet->setCellValue($columna . '1', $encabezado);
            $columna++;
        }
        // Agregar la columna "Motivo del Error"
        $sheet->setCellValue($columna . '1', 'Motivo del Error');

        // Estilo para las cabecera

        $sheet->getStyle('A1:' . $columna . '1')->applyFromArray($this->getHeaderStyle());

        // Llenar datos
        $row = 2;
        foreach ($errores as $error) {
            $columna = 'A';
            foreach ($encabezados as $key => $encabezado) {
                // Asignar los valores de las columnas de la fila
                $sheet->setCellValue($columna . $row, $error['fila'][$key] ?? '-');
                $columna++;
            }
            // Asignar el motivo del error en la última columna
            $sheet->setCellValue($columna . $row, $error['motivo']);
            $row++;
        }

        // Ajustar ancho de columnas
        foreach (range('A', $columna) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Crear archivo Excel
        $writer = new Xlsx($spreadsheet);

        $response = new StreamedResponse(
            function () use ($writer) {
                $writer->save('php://output');
            }
        );

        $response->headers->set('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->headers->set('Content-Disposition', 'attachment;filename="' . $nombreArchivo . '"');
        $response->headers->set('Cache-Control', 'max-age=0');

        return $response;
    }
}