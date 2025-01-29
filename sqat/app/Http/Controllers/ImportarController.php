<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Activo;

class ImportarController extends Controller
{
    public function index()
    {
        // Lógica para la vista o los datos que quieras mostrar
        return view('/importar');
    }

    public function importExcel(Request $request){
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);
        $path = $request->file('file')->getRealPath();
        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();
        $data = $sheet->toArray(null, true, true, false);
        $datawithoutheader = array_slice($data, 1);
        //dd($data);


        return view('/importar', ['data' => $datawithoutheader]);
    }
}
