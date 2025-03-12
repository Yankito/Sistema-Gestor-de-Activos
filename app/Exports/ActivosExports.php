<?php

namespace App\Exports;

use App\Models\Activo;
use App\Models\TipoActivo;
use App\Models\Estado;
use App\Models\Persona;
use App\Models\Ubicacion;
use App\Models\Asignacion; // Importar el modelo Asignacion
use App\Models\CaracteristicaAdicional; // Importar el modelo CaracteristicaAdicional
use App\Models\ValorAdicional; // Importar el modelo ValorAdicional
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
        // Obtener todos los activos con sus relaciones
        $activos = Activo::with(['tipoDeActivo', 'estadoRelation', 'responsableDeActivo', 'ubicacionRelation', 'valoresAdicionales.idCaracteristica'])->get();

        // Obtener los tipos de activo únicos
        $tiposActivo = TipoActivo::with('caracteristicasAdicionales')->get();

        // Crear el archivo de Excel
        $spreadsheet = new Spreadsheet();

        // Hoja general
        $this->crearHojaGeneral($spreadsheet, $activos);

        // Hojas por tipo de activo
        foreach ($tiposActivo as $tipo) {
            $this->crearHojaPorTipoActivo($spreadsheet, $tipo, $activos);
        }

        // Guardar el archivo
        $filePath = tempnam(sys_get_temp_dir(), 'activos');
        if ($formato == 'excel') {
            $writer = new Xlsx($spreadsheet);
            $filePath .= '.xlsx';
        } elseif ($formato == 'csv') {
            // No es compatible con múltiples hojas, por lo que solo exportará la hoja general
            $writer = new Csv($spreadsheet);
            $filePath .= '.csv';
        }
        $writer->save($filePath);

        return $filePath;
    }

    /**
     * Crea la hoja general con los datos de todos los activos.
     */
    private function crearHojaGeneral($spreadsheet, $activos)
    {
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('General');

        // Encabezados
        $headers = [
            'Número de Serie', 'Marca', 'Modelo', 'Tipo de Activo', 'Estado', 'Usuario de Activo', 'Responsable de Activo', 'Precio', 'Ubicación', 'Justificación Doble Activo',
        ];

        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $this->aplicarEstiloEncabezado($sheet, $column . '1');
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }

        // Datos
        $row = 2;
        foreach ($activos as $activo) {
            $usuariosAsignados = $activo->usuarioDeActivo->pluck('nombre_completo')->toArray();
            $usuariosFormateados = implode("\n• ", $usuariosAsignados);
            if (!empty($usuariosFormateados)) {
                $usuariosFormateados = "• " . $usuariosFormateados;
            }

            $sheet->setCellValue('A' . $row, $activo->nro_serie)
                  ->setCellValue('B' . $row, $activo->marca)
                  ->setCellValue('C' . $row, $activo->modelo)
                  ->setCellValue('D' . $row, $activo->tipoDeActivo->nombre)
                  ->setCellValue('E' . $row, $activo->estadoRelation->nombre_estado)
                  ->setCellValue('F' . $row, $usuariosFormateados)
                  ->setCellValue('G' . $row, $activo->responsableDeActivo->nombre_completo ?? 'Desconocido')
                  ->setCellValue('H' . $row, $activo->precio)
                  ->setCellValue('I' . $row, $activo->ubicacionRelation->sitio)
                  ->setCellValue('J' . $row, $activo->justificacion_doble_activo);

            $this->aplicarEstiloCeldas($sheet, 'A' . $row . ':J' . $row);

            if (count($usuariosAsignados) > 1) {
                $sheet->getRowDimension($row)->setRowHeight(15 * count($usuariosAsignados));
            }

            $row++;
        }

        // Formato de número para precios
        $sheet->getStyle('H2:H' . $row)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);
    }

    /**
     * Crea una hoja por tipo de activo con las características adicionales.
     */
    private function crearHojaPorTipoActivo($spreadsheet, $tipo, $activos)
    {
        $sheet = $spreadsheet->createSheet();
        $sheet->setTitle(substr($tipo->nombre, 0, 31)); // Limitar el nombre de la hoja a 31 caracteres

        // Encabezados fijos
        $headers = ['Número de Serie', 'Marca', 'Modelo', 'Tipo de Activo'];

        // Agregar encabezados para las características adicionales
        foreach ($tipo->caracteristicasAdicionales as $caracteristica) {
            $headers[] = $caracteristica->nombre_caracteristica;
        }

        // Escribir encabezados
        $column = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($column . '1', $header);
            $this->aplicarEstiloEncabezado($sheet, $column . '1');
            $sheet->getColumnDimension($column)->setAutoSize(true);
            $column++;
        }

        // Filtrar activos por tipo
        $activosFiltrados = $activos->where('tipo_de_activo', $tipo->id);

        // Escribir datos
        $row = 2;
        foreach ($activosFiltrados as $activo) {
            $sheet->setCellValue('A' . $row, $activo->nro_serie)
                  ->setCellValue('B' . $row, $activo->marca)
                  ->setCellValue('C' . $row, $activo->modelo)
                  ->setCellValue('D' . $row, $tipo->nombre);

            $column = 'E';
            foreach ($tipo->caracteristicasAdicionales as $caracteristica) {
                $valor = $activo->valoresAdicionales
                    ->where('id_caracteristica', $caracteristica->id)
                    ->first()
                    ->valor ?? 'N/A';
                $sheet->setCellValue($column . $row, $valor);
                $column++;
            }

            $this->aplicarEstiloCeldas($sheet, 'A' . $row . ':' . $column . $row);
            $row++;
        }
    }

    /**
     * Aplica estilos a los encabezados.
     */
    private function aplicarEstiloEncabezado($sheet, $celda)
    {
        $sheet->getStyle($celda)->applyFromArray([
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
    }

    /**
     * Aplica estilos a las celdas de datos.
     */
    private function aplicarEstiloCeldas($sheet, $rango)
    {
        $sheet->getStyle($rango)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['argb' => 'FF000000'],
                ],
            ],
        ]);
    }
}
?>
