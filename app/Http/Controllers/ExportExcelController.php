<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PHPExcel;
use PHPExcel_Style_Border;
use PHPExcel_Style_Fill;
use PHPExcel_IOFactory;
use App\FilaElectoral;
use DB;

class ExportExcelController extends Controller
{
  public function filasElectorales () {
    $filasElectorales = FilaElectoral::join('municipios', 'fila_electorals.id_municipio', '=', 'municipios.id')
                                     ->join('corporacions', 'fila_electorals.id_corporacion', '=', 'corporacions.id')
                                     ->select('fila_electorals.*', 'municipios.nombre as municipio_nombre', 'corporacions.nombre as corporacion_nombre')
                                     ->orderBy('municipios.nombre')
                                     ->orderBy('anio', 'desc')->get();

    $orden = array(
      ['Municipio', 'municipio_nombre'],
      ['Corporación', 'corporacion_nombre'],
      ['Votos totales', 'votostotales'],
      ['Votos candidato', 'votoscandidato'],
      ['Votos partido', 'votospartido'],
      ['Potencial electoral', 'potencialelectoral'],
      ['Año', 'anio']
    );

    $this->exportar($filasElectorales, $orden, 7, 'Información Electoral');

    return redirect('/Administracion/InfoElectoral');
  }

  public function lideres () {

  }

  public function compromisos () {

  }

  public function corporaciones () {
  }

  public function usuarios () {

  }

  public function permisos () {

  }

  public function tiposUsuario () {

  }

  public function roles() {
    
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
        $objPHPExcel->getActiveSheet()->setCellValue($letras[$a].$i, $datosRow->{$ordenRow[1]});
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
