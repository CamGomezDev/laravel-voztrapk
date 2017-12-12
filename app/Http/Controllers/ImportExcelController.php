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
  public function __construct() {
    $this->middleware('auth');
  }
  
  public function importar (Request $request, $cosa) {
    $this->validate($request, [
      'archivo' => 'required|file|max:4999'
    ]);

    $fileNameWithExt = $request->file('archivo')->getClientOriginalName();
    $fileName = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
    $extension = $request->file('archivo')->getClientOriginalExtension();
    $path = $request->file('archivo')->storeAs('public/imports', $fileNameWithExt);

    if ($extension != "xlsx" && $extension != "xls" && $extension != "csv") {
      return redirect($request->input('ruta'))->with('error', 'El archivo debe tener una terminación XLSX, XLS o CSV.');
    }
  
    error_reporting(E_ALL);
    set_time_limit(0);

    $objPHPExcel = PHPExcel_IOFactory::load('storage/imports/'.$fileNameWithExt);
    
    $sheetData = $objPHPExcel->getActiveSheet()->toArray(null,true,true,true);

    switch ($cosa) {
      case 'InfoElectoralAnt':
        $mens = $this->filasElectorales($sheetData, $fileNameWithExt, 'municipio');
        break;
      case 'InfoElectoralMed':
        $mens = $this->filasElectorales($sheetData, $fileNameWithExt, 'comuna');
        break;
      case 'LideresAnt':
        $mens = $this->lideres($sheetData, $fileNameWithExt, 'municipio');
        break;
      case 'LideresMed':
        $mens = $this->lideres($sheetData, $fileNameWithExt, 'comuna');
        break;
        
        default:
        break;
    }
    
    Storage::delete('public/imports/'.$fileNameWithExt);

    if (count($mens[0]) && count($mens[1])) {
      $suc = 'Se han subido exitosamente las filas: ';
      foreach($mens[0] as $sucrow) {
        $suc .= $sucrow.', ';
      }
      $suc = rtrim($suc, ', ');
      $suc .= '.';
  
      $err = 'Ha habido un fallo subiendo las filas: ';
      foreach($mens[1] as $errrow) {
        $err .= $errrow.', ';
      }
      $err = rtrim($err, ', ');
      $err .= '. Por favor revise en ellas que todos los elementos estén bien escritos. Al subirlos nuevamente, no lo haga con el mismo archivo ya que esto generará que todas las filas que se subieron exitosamente se repitan. Si recarga esta página este mensaje de error se perderá.';
      return redirect($request->input('ruta'))->with('success', $suc)
                                              ->with('error', $err);
    } elseif (count($mens[0])) {
      return redirect($request->input('ruta'))->with('success', 'Todas las filas se subieron exitosamente.');
    } elseif (count($mens[1])) {
      return redirect($request->input('ruta'))->with('error', 'Ninguna fila se ha subido debido a que en todas ellas se ha encontrado un error. Por favor revise en cada una que todos sus elementos estén bien escritos.');
    } else {
      return redirect($request->input('ruta'))->with('error', 'Su archivo no tiene filas para subir al sistema. Recuerde que la primera fila nunca es leída debido a que esta se deja libre para títulos.');
    }
  }
  
  public function filasElectorales ($sheetData, $fileNameWithExt, $opc) {
    $i = -1;
    $s = sizeof($sheetData);
    $mens = [[],[]];
    foreach ($sheetData as $row) {
      $i = ++$i;
      if ($i != 0) {
        $filaElectoral = new FilaElectoral;
        try {
          $filaElectoral->{'id_'.$opc}       = DB::table($opc.'s')->select('id')->where('nombre', '=', $row['A'])->first()->id;
          $filaElectoral->id_corporacion     = DB::table('corporacions')->select('id')->where('nombre', '=', $row['B'])->first()->id;
          $filaElectoral->potencialelectoral = $row['C'];
          $filaElectoral->votostotales       = $row['D'];
          $filaElectoral->votospartido       = $row['E'];
          $filaElectoral->votoscandidato     = $row['F'];
          $filaElectoral->anio               = $row['G'];

          $filaElectoral->save();
          $mens[0][] = $i+1;

        } catch (\Exception $e) {
          $mens[1][] = $i+1;
          continue;
        }
      }
    }
    return $mens;
  }

  public function lideres ($sheetData, $fileNameWithExt, $opc) {
    $i = -1;
    $s = sizeof($sheetData);
    $mens = [[],[]];
    foreach ($sheetData as $row) {
      $i = ++$i;
      if ($i != 0) {
        try {
          $lider = new Lider;
          $lider->{'id_'.$opc}   = DB::table($opc.'s')->select('id')->where('nombre', '=', $row['A'])->first()->id;
          $lider->nombre         = $row['B'];
          $lider->cedula         = $row['C'];
          $lider->correo         = $row['D'];
          $lider->telefono       = $row['E'];
          $lider->nivel          = $row['F'];
          $lider->tipolider      = $row['G'];
          if ($row['H'] == 1 || $row['H'] == 'activo') {
            $activo = 1;
          } elseif ($row['H'] == 0 || $row['H'] == 'inactivo') {
            $activo = 0;
          }
          $lider->activo         = $activo;
          if ($opc == 'comuna') {
            $lider->puesto_votacion_id = DB::table('puesto_votacions')->select('id')->where('nombre', '=', $row['I'])->first()->id;
            $lider->votosestimados    = $row['J'];
          } else {
            $lider->votosestimados = $row['I'];
          }
  
          $lider->save();
          $mens[0][] = $i + 1;
        } catch (\Exception $e) {
          $mens[1][] = $i + 1;
          continue;
        }
      }
    }
    return $mens;
  }
}