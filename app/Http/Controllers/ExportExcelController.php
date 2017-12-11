<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPExcel;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_IOFactory;
use App\FilaElectoral;
use App\Lider;
use App\Compromiso;
use App\Municipio;
use App\Comuna;
use App\Corporacion;
use App\Visita;
use App\PuestoVotacion;
use App\Subregion;
use DB;

class ExportExcelController extends Controller
{
  public function __construct() {
    $this->middleware('auth');
    $this->middleware('rol:1,3,4');
    $this->middleware('rol:1,3',['except' => ['filasElectoralesMapaAnt',
                                              'filasElectoralesMapaMed',
                                              'filasElectoralesMapaSub',
                                              'lideresMapaAnt',
                                              'lideresMapaMed',
                                              'lideresMapaSub',
                                              'resumenSub',
                                              'resumenCom',
                                              'exportar']]);
  }

  public function filasElectoralesAnt () {
    $filasElectorales = FilaElectoral::join('municipios', 'fila_electorals.id_municipio', '=', 'municipios.id')
                                     ->join('corporacions', 'fila_electorals.id_corporacion', '=', 'corporacions.id')
                                     ->select('fila_electorals.*', 'municipios.nombre as municipio_nombre', 'corporacions.nombre as corporacion_nombre')
                                     ->whereNotNull('fila_electorals.id_municipio')
                                     ->orderBy('municipios.nombre')
                                     ->orderBy('anio', 'desc')->get();

    $orden = array(
      ['Municipio', 'municipio_nombre'],
      ['Corporación', 'corporacion_nombre'],
      ['Potencial electoral', 'potencialelectoral'],
      ['Votos totales', 'votostotales'],
      ['Votos partido', 'votospartido'],
      ['Votos candidato', 'votoscandidato'],
      ['Año', 'anio']
    );

    $this->exportar($filasElectorales, $orden, 7, 'Info. electoral de municipios');

    return redirect('/Administracion/InfoElectoral');
  }

  public function filasElectoralesMed () {
    $filasElectorales = FilaElectoral::join('comunas', 'fila_electorals.id_comuna', '=', 'comunas.id')
                                     ->join('corporacions', 'fila_electorals.id_corporacion', '=', 'corporacions.id')
                                     ->select('fila_electorals.*', 'comunas.nombre as comuna_nombre', 'corporacions.nombre as corporacion_nombre')
                                     ->whereNotNull('fila_electorals.id_comuna')
                                     ->orderBy('comunas.nombre')
                                     ->orderBy('anio', 'desc')->get();

    $orden = array(
      ['Comuna', 'comuna_nombre'],
      ['Corporación', 'corporacion_nombre'],
      ['Potencial electoral', 'potencialelectoral'],
      ['Votos totales', 'votostotales'],
      ['Votos partido', 'votospartido'],
      ['Votos candidato', 'votoscandidato'],
      ['Año', 'anio']
    );

    $this->exportar($filasElectorales, $orden, 7, 'Info. electoral de comunas');

    return redirect('/Administracion/InfoElectoral');
  }

  public function lideresAnt () {
    $lideres = Lider::join('municipios', 'liders.id_municipio', '=', 'municipios.id')
                    ->select('liders.*', 'municipios.nombre as municipio_nombre')
                    ->whereNotNull('id_municipio')
                    ->orderBy('nombre')
                    ->get();

    $orden = array(
      ['Municipio', 'municipio_nombre'],
      ['Nombre', 'nombre'],
      ['Cédula', 'cedula'],
      ['Correo', 'correo'],
      ['Teléfono', 'telefono'],
      ['Nivel', 'nivel'],
      ['Tipo de líder', 'tipolider'],
      ['Activo', 'activo'],
      ['Votos estimados', 'votosestimados']
    );

    $this->exportar($lideres, $orden, 9, 'Líderes en municipios');
  }

  public function lideresMed () {
    $lideres = Lider::join('comunas', 'liders.id_comuna', '=', 'comunas.id')
                    ->join('puesto_votacions', 'liders.puesto_votacion_id', '=', 'puesto_votacions.id')
                    ->join('barrios', 'puesto_votacions.barrio_id', '=', 'barrios.id')
                    ->select('liders.*', 'comunas.nombre as comuna_nombre', 'puesto_votacions.nombre as puesto_votacion_nombre', 'barrios.nombre as barrio_nombre')
                    ->whereNotNull('id_comuna')
                    ->orderBy('nombre')
                    ->get();

    $orden = array(
      ['Comuna', 'comuna_nombre'],
      ['Nombre', 'nombre'],
      ['Cédula', 'cedula'],
      ['Correo', 'correo'],
      ['Teléfono', 'telefono'],
      ['Nivel', 'nivel'],
      ['Tipo de líder', 'tipolider'],
      ['Activo', 'activo'],
      ['Puesto de votación', 'puesto_votacion_nombre'],
      ['Barrio', 'barrio_nombre'],
      ['Votos estimados', 'votosestimados']
    );

    $this->exportar($lideres, $orden, 11, 'Líderes en comunas');
  }

  public function compromisosAnt () {
    $compromisos = Compromiso::join('liders', 'compromisos.id_lider', '=', 'liders.id')
                             ->select('compromisos.*', 'liders.nombre as lider_nombre')
                             ->whereNotNull('liders.id_municipio')
                             ->orderBy('liders.nombre')
                             ->get();

    $orden = array(
      ['Líder', 'lider_nombre'],
      ['Nombre', 'nombre'],
      ['Descripción', 'descripcion'],
      ['Cumplimiento', 'cumplimiento', 'Cumplido', 'Pendiente'],
      ['Costo', 'costo']
    );

    $this->exportar($compromisos, $orden, 5, 'Compromisos en municipios');
  }

  public function compromisosMed () {
    $compromisos = Compromiso::join('liders', 'compromisos.id_lider', '=', 'liders.id')
                             ->select('compromisos.*', 'liders.nombre as lider_nombre')
                             ->whereNotNull('liders.id_comuna')
                             ->orderBy('liders.nombre')
                             ->get();

    $orden = array(
      ['Líder', 'lider_nombre'],
      ['Nombre', 'nombre'],
      ['Descripción', 'descripcion'],
      ['Cumplimiento', 'cumplimiento', 'Cumplido', 'Pendiente'],
      ['Costo', 'costo']
    );

    $this->exportar($compromisos, $orden, 5, 'Compromisos en comunas');
  }

  public function corporaciones () {
    $corporaciones = Corporacion::orderBy('nombre')->get();

    $orden = array(
      ['Corporación', 'nombre']
    );

    $this->exportar($corporaciones, $orden, 1, 'Corporaciones');
  }

  public function visitas () {
    $visitas = Visita::join('municipios', 'visitas.id_municipio', '=', 'municipios.id')
                     ->select('municipios.nombre as municipio_nombre', 'visitas.*')
                     ->orderBy('municipios.nombre')->get();

    $orden = array(
      ['Municipio', 'municipio_nombre'],
      ['Notas', 'notas'],
      ['Llegada', 'llegada'],
      ['Salida', 'salida']
    );

    $this->exportar($visitas, $orden, 4, 'Visitas');
  }

  public function puestos () {
    $puestos = PuestoVotacion::join('comunas', 'puesto_votacions.comuna_id', '=', 'comunas.id')
                             ->join('barrios', 'puesto_votacions.barrio_id', '=', 'barrios.id')
                             ->select('puesto_votacions.*', 'comunas.nombre as comuna_nombre', 'barrios.nombre as barrio_nombre')
                             ->get();

    $orden = array(
      ['Comuna', 'comuna_nombre'],
      ['Barrio', 'barrio_nombre'],
      ['Nombre', 'nombre'],
      ['Mesas', 'mesas']
    );

    $this->exportar($puestos, $orden, 4, 'Puestos de votación');
  }

  public function filasElectoralesMapaAnt ($id) {
    $filasElectorales = FilaElectoral::join('corporacions', 'fila_electorals.id_corporacion', '=', 'corporacions.id')
                                     ->select('fila_electorals.*', 'corporacions.nombre as corporacion')
                                     ->where('fila_electorals.id_municipio', '=', $id)
                                     ->orderBy('anio', 'desc')
                                     ->get();
    
    $orden = array(
      ['Corporación', 'corporacion'],
      ['Potencial electoral', 'potencialelectoral'],
      ['Votos totales', 'votostotales'],
      ['Votos partido', 'votospartido'],
      ['Votos candidato', 'votoscandidato']
    );

    $this->exportar($filasElectorales, $orden, 5, 'Info. '.(Municipio::find($id))->nombre);
  }

  public function filasElectoralesMapaMed ($id) {
    $filasElectorales = FilaElectoral::join('corporacions', 'fila_electorals.id_corporacion', '=', 'corporacions.id')
                                     ->select('fila_electorals.*', 'corporacions.nombre as corporacion')
                                     ->where('fila_electorals.id_comuna', '=', $id)
                                     ->orderBy('anio', 'desc')
                                     ->get();
    
    $orden = array(
      ['Corporación', 'corporacion'],
      ['Potencial electoral', 'potencialelectoral'],
      ['Votos totales', 'votostotales'],
      ['Votos partido', 'votospartido'],
      ['Votos candidato', 'votoscandidato']
    );

    $this->exportar($filasElectorales, $orden, 5, 'Inf. '.Comuna::find($id)->nombre);
  }

  public function filasElectoralesMapaSub ($id) {
    $filasElectorales = DB::select(DB::raw("SELECT
                                              SUM(fila_electorals.votoscandidato) AS votoscandidato,
                                              SUM(fila_electorals.votospartido) AS votospartido,
                                              SUM(fila_electorals.votostotales) AS votostotales,
                                              SUM(fila_electorals.potencialelectoral) AS potencialelectoral,
                                              fila_electorals.id_corporacion AS id_corporacion,
                                              fila_electorals.anio AS anio
                                            FROM (fila_electorals JOIN
                                              municipios ON fila_electorals.id_municipio = municipios.id
                                            ) WHERE municipios.id_subregion = {$id} 
                                            GROUP BY fila_electorals.id_corporacion, fila_electorals.anio"));
    foreach ($filasElectorales as $filaElectoral) {
      $filaElectoral->corporacion_nombre = Corporacion::find($filaElectoral->id_corporacion)->nombre;
    }
    
    $orden = array(
      ['Corporación', 'corporacion_nombre'],
      ['Potencial electoral', 'potencialelectoral'],
      ['Votos totales', 'votostotales'],
      ['Votos partido', 'votospartido'],
      ['Votos candidato', 'votoscandidato']
    );

    $this->exportar($filasElectorales, $orden, 5, 'Info. Subregión '.Subregion::find($id)->nombre);
  }

  public function lideresMapaAnt ($id) {
    $lideres = Lider::where('id_municipio', '=', $id)
                    ->orderBy('nombre')
                    ->get();

    $orden = array(
      ['Nombre', 'nombre'],
      ['Cédula', 'cedula'],
      ['Correo', 'correo'],
      ['Teléfono', 'telefono'],
      ['Nivel', 'nivel'],
      ['Tipo de líder', 'tipolider'],
      ['Activo', 'activo', 'Activo', 'Inactivo'],
      ['Votos estimados', 'votosestimados']
    );

    $this->exportar($lideres, $orden, 8, 'Líds. '.Municipio::find($id)->nombre);
  }

  public function lideresMapaMed ($id) {
    $lideres = Lider::join('puesto_votacions', 'liders.puesto_votacion_id', '=', 'puesto_votacions.id')
                    ->join('barrios', 'puesto_votacions.barrio_id', '=', 'barrios.id')
                    ->select('liders.*', 'puesto_votacions.nombre as puesto_nombre', 'barrios.nombre as barrio_nombre')
                    ->where('id_comuna', '=', $id)
                    ->orderBy('nombre')
                    ->get();

    $orden = array(
      ['Nombre', 'nombre'],
      ['Cédula', 'cedula'],
      ['Correo', 'correo'],
      ['Teléfono', 'telefono'],
      ['Nivel', 'nivel'],
      ['Tipo de líder', 'tipolider'],
      ['Activo', 'activo', 'Activo', 'Inactivo'],
      ['Puesto de votación', 'puesto_nombre'],
      ['Barrio', 'barrio_nombre'],
      ['Votos estimados', 'votosestimados']
    );

    $this->exportar($lideres, $orden, 10, 'Líd. '.Comuna::find($id)->nombre);
  }

  public function lideresMapaSub ($id) {
    $lideres = Lider::
    // whereHas('municipio', function($query) use ($id) {
    //                     $query->where('id_subregion', '=', $id);
    //                   })
                    join('municipios', 'liders.id_municipio', '=', 'municipios.id')
                    ->where('municipios.id_subregion', '=', $id)
                    ->select('liders.*', 'municipios.nombre as municipio_nombre')
                    ->orderBy('municipios.nombre')
                    ->orderBy('liders.nombre')
                    ->get();

    $orden = array(
      ['Municipio', 'municipio_nombre'],
      ['Nombre', 'nombre'],
      ['Cédula', 'cedula'],
      ['Correo', 'correo'],
      ['Teléfono', 'telefono'],
      ['Nivel', 'nivel'],
      ['Tipo de líder', 'tipolider'],
      ['Activo', 'activo', 'Activo', 'Inactivo'],
      ['Votos estimados', 'votosestimados']
    );

    $this->exportar($lideres, $orden, 9, 'Líds. Subregión '.Subregion::find($id)->nombre);
  }

  public function puestosMapaMed ($id) {
    $puestos = PuestoVotacion::join('barrios', 'puesto_votacions.barrio_id', '=', 'barrios.id')
                             ->select('puesto_votacions.*', 'barrios.nombre as barrio_nombre')
                             ->where('puesto_votacions.comuna_id', '=', $id)->get();

    $orden = array(
      ['Nombre', 'nombre'],
      ['Mesas', 'mesas'],
      ['Barrio', 'barrio_nombre']
    );

    $this->exportar($puestos, $orden, 3, 'Ps-'.Comuna::find($id)->nombre);
  }

  public function resumenSub (Request $request) {
    $idcorp = DB::connection()->getPdo()->quote($request->get('id_corporacion'));
    $anio   = DB::connection()->getPdo()->quote($request->get('anio'));

    $subregiones = Subregion::get();
    foreach ($subregiones as $subregion) {
      $resumenfilasElec = DB::select(DB::raw("SELECT
                                                SUM(fila_electorals.votoscandidato) AS votoscandidato,
                                                SUM(fila_electorals.votospartido) AS votospartido,
                                                SUM(fila_electorals.votostotales) AS votostotales,
                                                SUM(fila_electorals.potencialelectoral) AS potencialelectoral
                                              FROM (fila_electorals JOIN
                                                municipios ON fila_electorals.id_municipio = municipios.id
                                              ) WHERE municipios.id_subregion = {$subregion->id} AND
                                                fila_electorals.anio = {$anio} AND
                                                fila_electorals.id_corporacion = {$idcorp}"))[0];
      $resumenVotosEsti = DB::select(DB::raw("SELECT SUM(liders.votosestimados) AS votosestimados
                                              FROM (liders JOIN municipios ON liders.id_municipio = municipios.id)
                                              WHERE municipios.id_subregion = {$subregion->id}"))[0];

      $subregion->votoscandidato     = ($resumenfilasElec->votoscandidato) ? $resumenfilasElec->votoscandidato : 0;
      $subregion->votospartido       = ($resumenfilasElec->votospartido) ? $resumenfilasElec->votospartido : 0;
      $subregion->votostotales       = ($resumenfilasElec->votostotales) ? $resumenfilasElec->votostotales : 0;
      $subregion->votosestimados     = ($resumenVotosEsti->votosestimados) ? $resumenVotosEsti->votosestimados : 0;
      $subregion->potencialelectoral = ($resumenfilasElec->potencialelectoral) ? $resumenfilasElec->potencialelectoral : 0;
    }

    $orden = array (
      ['Subregión', 'nombre'],
      ['Votos candidato', 'votoscandidato'],
      ['Votos partido', 'votospartido'],
      ['Votos totales', 'votostotales'],
      ['Votos estimados', 'votosestimados'],
      ['Potencial electoral', 'potencialelectoral']
    );

    $this->exportar($subregiones, $orden, 6, 'Resumen por subregión');
  }

  public function resumenCom (Request $request) {
    $idcorp = DB::connection()->getPdo()->quote($request->get('id_corporacion'));
    $anio   = DB::connection()->getPdo()->quote($request->get('anio'));

    $comunas = Comuna::get();
    foreach ($comunas as $comuna) {
      $resumenfilasElec = DB::select(DB::raw("SELECT
                                                SUM(fila_electorals.votoscandidato) AS votoscandidato,
                                                SUM(fila_electorals.votospartido) AS votospartido,
                                                SUM(fila_electorals.votostotales) AS votostotales,
                                                SUM(fila_electorals.potencialelectoral) AS potencialelectoral
                                              FROM fila_electorals
                                              WHERE fila_electorals.id_comuna = {$comuna->id} AND
                                                fila_electorals.anio = {$anio} AND
                                                fila_electorals.id_corporacion = {$idcorp}"))[0];
      $resumenVotosEsti = DB::select(DB::raw("SELECT SUM(liders.votosestimados) AS votosestimados
                                              FROM liders
                                              WHERE liders.id_comuna = {$comuna->id}"))[0];

      $comuna->votoscandidato     = ($resumenfilasElec->votoscandidato) ? $resumenfilasElec->votoscandidato : 0;
      $comuna->votospartido       = ($resumenfilasElec->votospartido) ? $resumenfilasElec->votospartido : 0;
      $comuna->votostotales       = ($resumenfilasElec->votostotales) ? $resumenfilasElec->votostotales : 0;
      $comuna->votosestimados     = ($resumenVotosEsti->votosestimados) ? $resumenVotosEsti->votosestimados : 0;
      $comuna->potencialelectoral = ($resumenfilasElec->potencialelectoral) ? $resumenfilasElec->potencialelectoral : 0;
    }

    $orden = array (
      ['Nombre', 'nombre'],
      ['Potencial electoral', 'potencialelectoral'],
      ['Votos totales', 'votostotales'],
      ['Votos partido', 'votospartido'],
      ['Votos candidato', 'votoscandidato'],
      ['Votos estimados', 'votosestimados']
    );

    $this->exportar($comunas, $orden, 6, 'Resumen por comuna');
  }

  public function exportar ($datos, $orden, $colsNum, $nombre) {
    // Define styles
    $styleAllArray = array(
      'borders' => array(
        'allborders' => array(
          'style' => PHPExcel_Style_Border::BORDER_THIN
        ), 
        'outline' => array(
          'style' => PHPExcel_Style_Border::BORDER_MEDIUM
        )
      )
    );
    $styleTitleArray = array(
      'font' => array(
        'bold' => true
      ),
      'borders' => array(
        'outline' => array(
          'style' => PHPExcel_Style_Border::BORDER_MEDIUM
        )
      ),
      'fill' => array(
        'type' => PHPExcel_Style_Fill::FILL_SOLID,
        'color' => array(
          'rgb' => '71A3F2'
        )
      )
    );  

    $letras = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K');

    // Create new PHPExcel object
    $objPHPExcel = new PHPExcel();
    
    // Add some data
    $objPHPExcel->setActiveSheetIndex(0);

    $e = -1;
    foreach ($orden as $row) {
      $e = ++$e;
      $objPHPExcel->getActiveSheet()->setCellValue($letras[$e].'1', $row[0]);
    }

    $i = 1;
    foreach ($datos as $datosRow) {
      $i = ++$i;

      $a = -1;
      foreach ($orden as $ordenRow) {
        $a = ++$a;
        if (isset($ordenRow[2])) {
          if ($datosRow->{$ordenRow[1]}) {
            $fillInfo = $ordenRow[2];
          } else {
            $fillInfo = $ordenRow[3];
          }
        } else {
          $fillInfo = $datosRow->{$ordenRow[1]};
        }
        $objPHPExcel->getActiveSheet()->setCellValue($letras[$a].$i, $fillInfo);
      }
    }

    // Set styles
    $dim = ($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
    $objPHPExcel->getActiveSheet()->getStyle($dim)->applyFromArray($styleAllArray);
    $objPHPExcel->getActiveSheet()->getStyle('A1:'.$letras[$colsNum-1].'1')->applyFromArray($styleTitleArray);
    
    // Set autofilter
    $objPHPExcel->getActiveSheet()->setAutoFilter($objPHPExcel->getActiveSheet()->calculateWorksheetDimension());
    
    // Set autosize
    for ($o=0; $o < $colsNum; $o++) { 
      $objPHPExcel->getActiveSheet()->getColumnDimension($letras[$o])->setAutoSize('true');
    }
    
    // Rename worksheet
    $objPHPExcel->getActiveSheet()->setTitle($nombre);
    
    // Set active sheet index to the first sheet, so Excel opens this as the first sheet
    $objPHPExcel->setActiveSheetIndex(0);
    
    // Redirect output to a client’s web browser (Excel2007)
    header('Content-Type: application/vnd.ms-excel');
    header('Content-Disposition: attachment;filename="'.$nombre.'.xlsx"');
    header('Cache-Control: max-age=0');

    // If you're serving to IE 9, then the following may be needed
    header('Cache-Control: max-age=1');
    
    // If you're serving to IE over SSL, then the following may be needed
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
    header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
    header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
    header('Pragma: public'); // HTTP/1.0

    $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
    ob_end_clean();
    $objWriter->save('php://output');
    exit;
  }
}
