<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPExcel_IOFactory;
use App\FilaElectoral;
use DB;

class ImportExcelController extends Controller
{
  public function importar (Request $request, $cosa) {
    $this->validate($request, [
      'archivo' => 'required|file|max:4999'
    ]);

    $fileNameWithExt = $request->file('archivo')->getClientOriginalName();
    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    $extension = $request->file('archivo')->getClientOriginalExtension();
    $path = $request->file('archivo')->storeAs('public/imports', $fileNameWithExt);

    if ($extension != "xlsx" && $extension != "xls" && $extension != "csv") {
      return redirect($request->input('ruta'))->with('error', 'El archivo debe tener una terminación XLSX, CSV o XLS');
    }
  
    error_reporting(E_ALL);
    set_time_limit(0);
  
    $objPHPExcel = PHPExcel_IOFactory::load('storage\\imports\\'.$fileNameWithExt);
  
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

    switch ($cosa) {
      case 'InfoElectoral':
        $this->filasElectorales($sheetData, $fileNameWithExt);
        break;
        
        default:
        break;
    }
    Storage::delete('public/imports/'.$fileNameWithExt);
    return redirect($request->input('ruta'));
  }
  
  public function filasElectorales ($sheetData, $fileNameWithExt) {
    $i = -1;
    $s = sizeof($sheetData);
    foreach ($sheetData as $row) {
      $i = ++$i;
      if ($i != 0 && !is_null($row['A']) && !is_null($row['B'])) {
        $filaElectoral = new FilaElectoral;
        $filaElectoral->id_municipio       = DB::table('municipios')->select('id')->where('nombre', '=', $row['A'])->first()->id;
        $filaElectoral->id_corporacion     = DB::table('corporacions')->select('id')->where('nombre', '=', $row['B'])->first()->id;
        $filaElectoral->votostotales       = $row['C'];
        $filaElectoral->votoscandidato     = $row['D'];
        $filaElectoral->votospartido       = $row['E'];
        $filaElectoral->potencialelectoral = $row['F'];
        $filaElectoral->anio               = $row['G'];

        $filaElectoral->save();
      }
    }
  }
}