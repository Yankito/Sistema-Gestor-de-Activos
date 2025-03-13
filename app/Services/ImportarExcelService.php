<?php

namespace App\Services;

use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImportarExcelService
{
    /**
     * Carga un archivo Excel y devuelve los datos de la hoja activa.
     */
    public function cargarArchivo($archivo)
    {
        $spreadsheet = IOFactory::load($archivo->getPathname());
        return $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
    }

    /**
     * Valida el archivo Excel.
     */
    public function validarArchivo($request)
    {
        return $request->validate([
            'archivo_excel' => 'required|mimes:xlsx,xls|max:5120',
        ], [
            'archivo_excel.max' => 'El archivo no debe ser mayor a 5 MB.',
        ]);
    }

    /**
     * Maneja la transacción de la importación.
     */
    public function manejarTransaccion(callable $callback)
    {
        DB::beginTransaction();
        try {
            $resultado = $callback();
            DB::commit();
            return $resultado;
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error durante la importación: ' . $e->getMessage());
            throw $e;
        }
    }
}
