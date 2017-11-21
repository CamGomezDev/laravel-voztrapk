<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use PHPExcel_IOFactory;
use App\FilaElectoral;
use App\Lider;
use App\Municipio;
use App\Corporacion;
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
      case 'InfoElectoralAnt':
        $this->filasElectorales($sheetData, $fileNameWithExt, 'municipio');
        break;
      case 'InfoElectoralMed':
        $this->filasElectorales($sheetData, $fileNameWithExt, 'comuna');
        break;
      case 'LideresAnt':
        $this->lideres($sheetData, $fileNameWithExt, 'municipio');
        break;
      case 'LideresMed':
        $this->lideres($sheetData, $fileNameWithExt, 'comuna');
        break;
        
        default:
        break;
    }
    Storage::delete('public/imports/'.$fileNameWithExt);
    return redirect($request->input('ruta'))->with('success', 'Se han subido exitosamente todas las filas que estaban bien escritas.');
  }
  
  public function filasElectorales ($sheetData, $fileNameWithExt, $opc) {
    // $corporaciones = Corporacion::select('nombre')->get();
    // $municipios    = Municipio::select('nombre')->get();

    $i = -1;
    $s = sizeof($sheetData);
    // Validación. En proceso de construcción
    // foreach ($sheetData as $row) {
    //   $i = ++$i;
    //   if ($i != 0 && !is_null($row['A']) && !is_null($row['B']) && !is_null($row['C']) && !is_null($row['D']) && !is_null($row['E']) && !is_null($row['F']) && !is_null($row['G'])) {
    //     foreach ($municipios as $municipio) {
    //       if ($row['A'] == $municipio->nombre && )
    //     }
    //   } else {
    //     return 'La fila '.$i+1.' no puede tener un campo nulo.'
    //   }
    // }
    foreach ($sheetData as $row) {
      $i = ++$i;
      if ($i != 0 && !is_null($row['A']) && !is_null($row['B']) && !is_null($row['C']) && !is_null($row['D']) && !is_null($row['E']) && !is_null($row['F']) && !is_null($row['G'])) {
        $filaElectoral = new FilaElectoral;
        $filaElectoral->{'id_'.$opc}       = DB::table($opc.'s')->select('id')->where('nombre', '=', $row['A'])->first()->id;
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

  public function lideres ($sheetData, $fileNameWithExt, $opc) {
    $i = -1;
    $s = sizeof($sheetData);
    foreach ($sheetData as $row) {
      $i = ++$i;
      if ($i != 0 && !is_null($row['A']) && !is_null($row['B']) && !is_null($row['C']) && !is_null($row['D']) && !is_null($row['E']) && !is_null($row['F']) && !is_null($row['G'])) {
        $lider = new Lider;
        $lider->nombre         = $row['A'];
        $lider->cedula         = $row['B'];
        $lider->correo         = $row['C'];
        $lider->telefono       = $row['D'];
        $lider->nivel          = $row['E'];
        $lider->tipolider      = $row['F'];
        if ($row['G'] == 1 || $row['G']) {
          $activo = $row['G'];
        } elseif ($row['G'] == 'activo') {
          $activo = 1;
        } elseif ($row['G'] == 'inactivo') {
          $activo = 0;
        } else {
          $activo = $row['G'];
        }
        $lider->activo         = $row['G'];
        $lider->votosestimados = $row['H'];
        $lider->{'id_'.$opc}   = DB::table($opc.'s')->select('id')->where('nombre', '=', $row['I'])->first()->id;

        $lider->save();
      }
    }
  }
}