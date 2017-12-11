<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Municipio;
use App\Subregion;
use App\FilaElectoral;
use App\Lider;
use App\Compromiso;
use App\Corporacion;
use DB;

class MapaAntController extends Controller
{
  public function __construct () {
    $this->middleware('auth');
    $this->middleware('rol:1,3,4');
  }

  public function municipios () {
    $municipios = Municipio::select('id', 'nombre', 'poblacion')->get();
  
    foreach ($municipios as $municipio) {
      if (count($municipio->fila_electorals)) {
        $municipio->panel = 1;
        $info = FilaElectoral::select('votospartido', 'votoscandidato')
                             ->where('id_municipio', '=', $municipio->id)
                             ->orderBy('anio', 'desc')
                             ->first();
        $municipio->votospartido   = $info->votospartido;
        $municipio->votoscandidato = $info->votoscandidato;
      } else {
        $municipio->panel = 0;
      }
      unset($municipio->fila_electorals);
    }
    return $municipios;
  }

  public function index (Request $request) {
    if ($request->has('m')) {
      $id = $request->get('m');
      $municipio = Municipio::find($id);
    } else {
      $id = false;
      $municipio = null;
    }

    $municipios = $this->municipios();

    return view('pags.mapaAnt')->with('municipios', json_encode($municipios))
                               ->with('municipio', $municipio)
                               ->with('idmun', $id);
  }

  public function conseguir (Request $request) {
    $id       = $request->get('idwhatevs');
    $ejecutar = $request->get('ejecutar');
    $rows     = $request->get('rows');
    if($request->has('page')) {
      $page = $request->get('page');
    } else {
      $page = 1;
    }
    if($ejecutar == 'filasElectorales' || $ejecutar == 'lideres') {
      $municipio = Municipio::where('id', '=', $id)->first();
      $subregion = Subregion::where('id', '=', $municipio->id_subregion)->first();
      $cosafrase = $municipio->nombre.' - Subregión: '.$subregion->nombre.' - Población: '.(($municipio->poblacion) ? $municipio->poblacion : 0);
    }
    
    if($ejecutar == 'lideresSubs' || $ejecutar == 'filasElectoralesSubs') {
      $subregion = Subregion::find($id);
      $cosafrase = 'Subregión '.$subregion->nombre;
    }
    
    switch ($ejecutar) {
      case 'filasElectorales':
        $filasElectorales = FilaElectoral::where('id_municipio', '=', $id)
                                         ->orderBy('anio', 'desc')
                                         ->orderBy('id_corporacion', 'desc')
                                         ->paginate($rows);
        $totRows = FilaElectoral::where('id_municipio', '=', $id)->count();
        $totPags = ceil($totRows/$rows);
        return view('pags.mapa.filaselectorales')->with('filasElectorales', $filasElectorales)
                                                 ->with('cosa', $municipio)
                                                 ->with('cosafrase', $cosafrase)
                                                 ->with('editar', true)
                                                 ->with('totPags', $totPags)
                                                 ->with('totRows', $totRows)
                                                 ->with('rows', $rows)
                                                 ->with('page', $page)
                                                 ->with('idcosa', $id);
        break;

      case 'filasElectoralesSubs':
        $filasElectorales = DB::select(DB::raw("SELECT
                                                  SUM(fila_electorals.votoscandidato) AS votoscandidato,
                                                  SUM(fila_electorals.votospartido) AS votospartido,
                                                  SUM(fila_electorals.votostotales) AS votostotales,
                                                  SUM(fila_electorals.potencialelectoral) AS potencialelectoral,
                                                  fila_electorals.id_corporacion AS corporacion,
                                                  fila_electorals.anio AS anio
                                                FROM (fila_electorals JOIN
                                                  municipios ON fila_electorals.id_municipio = municipios.id
                                                ) WHERE municipios.id_subregion = {$subregion->id}
                                                GROUP BY fila_electorals.id_corporacion, fila_electorals.anio"));
        foreach ($filasElectorales as $filaElectoral) {
          $corpid = $filaElectoral->corporacion;
          $filaElectoral->corporacion = new \stdClass();
          $filaElectoral->corporacion->nombre = Corporacion::find($corpid)->nombre;
        }

        return view('pags.mapa.filaselectorales')->with('filasElectorales', $filasElectorales)
                                                 ->with('cosa', $subregion)
                                                 ->with('cosafrase', $cosafrase)
                                                 ->with('editar', false)
                                                 ->with('totPags', 1)
                                                 ->with('totRows', 5)
                                                 ->with('rows', 5)
                                                 ->with('page', 1)
                                                 ->with('idcosa', $id);
        break;

      case 'lideres':
        if ($request->get('busq')) {
          $q = $request->get('busq');
          $lideres = Lider::where(function($query) use ($q) {
                              $query->where('nombre', 'LIKE', '%'.$q.'%')
                                    ->orwhere('cedula', 'LIKE', '%'.$q.'%')
                                    ->orwhere('correo', 'LIKE', '%'.$q.'%')
                                    ->orwhere('telefono', 'LIKE', '%'.$q.'%')
                                    ->orwhere('nivel', 'LIKE', '%'.$q.'%')
                                    ->orwhere('tipolider', 'LIKE', '%'.$q.'%')
                                    ->orwhere('activo', 'LIKE', '%'.$q.'%')
                                    ->orwhere('votosestimados', '=', $q);
                            })
                          ->where('id_municipio', '=', $id)
                          ->orderBy('nombre', 'asc')
                          ->paginate($rows);
                          
          $totRows = Lider::where(function($query) use ($q) {
                              $query->where('nombre', 'LIKE', '%'.$q.'%')
                                    ->orwhere('cedula', 'LIKE', '%'.$q.'%')
                                    ->orwhere('correo', 'LIKE', '%'.$q.'%')
                                    ->orwhere('telefono', 'LIKE', '%'.$q.'%')
                                    ->orwhere('nivel', 'LIKE', '%'.$q.'%')
                                    ->orwhere('tipolider', 'LIKE', '%'.$q.'%')
                                    ->orwhere('activo', 'LIKE', '%'.$q.'%')
                                    ->orwhere('votosestimados', '=', $q);
                            })
                          ->where('id_municipio', '=', $id)
                          ->count();
        } else {
          $lideres = Lider::where('id_municipio', '=', $id)
                          ->orderBy('nombre', 'asc')
                          ->paginate($rows);
          $totRows = Lider::where('id_municipio', '=', $id)->count();
        }
        $votosestimados = DB::table('liders')->where('id_municipio', '=', $id)->sum('votosestimados');
        $totPags = ceil($totRows/$rows);
        return view('pags.mapa.lideres')->with('lideres', $lideres)
                                        ->with('cosa', $municipio->nombre . " - Votos estimados totales: ".$votosestimados)
                                        ->with('totPags', $totPags)
                                        ->with('totRows', $totRows)
                                        ->with('rows', $rows)
                                        ->with('page', $page)
                                        ->with('idcosa', $id);

      case 'lideresSubs':
        if ($request->get('busq')) {
          $q = $request->get('busq');
          $lideres = Lider::whereHas('municipio', function($query) use ($id, $q) {
                              $query->where('nombre', 'LIKE', '%'.$q.'%');
                            })
                          ->orWhere(function($query) use ($q) {
                              $query->where('nombre', 'LIKE', '%'.$q.'%')
                                    ->orwhere('cedula', 'LIKE', '%'.$q.'%')
                                    ->orwhere('correo', 'LIKE', '%'.$q.'%')
                                    ->orwhere('telefono', 'LIKE', '%'.$q.'%')
                                    ->orwhere('nivel', 'LIKE', '%'.$q.'%')
                                    ->orwhere('tipolider', 'LIKE', '%'.$q.'%')
                                    ->orwhere('activo', 'LIKE', '%'.$q.'%')
                                    ->orwhere('votosestimados', '=', $q);
                            })
                          ->whereHas('municipio', function($query) use ($id, $q) {
                              $query->where('id_subregion', '=', '%'.$q.'%');
                            })
                          ->orderBy('nombre', 'asc')
                          ->paginate($rows);

          $totRows = Lider::whereHas('municipio', function($query) use ($id, $q) {
                              $query->where('nombre', 'LIKE', '%'.$q.'%');
                            })
                          ->orWhere(function($query) use ($q) {
                              $query->where('nombre', 'LIKE', '%'.$q.'%')
                                    ->orwhere('cedula', 'LIKE', '%'.$q.'%')
                                    ->orwhere('correo', 'LIKE', '%'.$q.'%')
                                    ->orwhere('telefono', 'LIKE', '%'.$q.'%')
                                    ->orwhere('nivel', 'LIKE', '%'.$q.'%')
                                    ->orwhere('tipolider', 'LIKE', '%'.$q.'%')
                                    ->orwhere('activo', 'LIKE', '%'.$q.'%')
                                    ->orwhere('votosestimados', '=', $q);
                            })
                          ->whereHas('municipio', function($query) use ($id, $q) {
                              $query->where('id_subregion', '=', '%'.$q.'%');
                            })
                          ->count();
        } else {
          $lideres = Lider::whereHas('municipio', function($query) use ($id){
                              $query->where('id_subregion', '=', $id);
                            })
                          ->orderBy('nombre', 'asc')
                          ->paginate($rows);
          $totRows = Lider::whereHas('municipio', function($query) use ($id) {
                              $query->where('id_subregion', '=', $id);
                            })->count();
        }
        $totPags = ceil($totRows/$rows);
        $votosestimados = 0;
        $lideresvotes = Lider::select('votosestimados')
                             ->whereHas('municipio', function($query) use ($id) {
                                 $query->where('id_subregion', '=', $id);
                               })->get();
        foreach ($lideresvotes as $lid) {
          $votosestimados = $votosestimados + $lid->votosestimados;
        }                            
        return view('pags.mapa.lideres')->with('lideres', $lideres)
                                        ->with('cosa', 'la subregión '.$subregion->nombre.' - Votos estimados totales: '.$votosestimados)
                                        ->with('totPags', $totPags)
                                        ->with('totRows', $totRows)
                                        ->with('rows', $rows)
                                        ->with('page', $page)
                                        ->with('issetsub', 1)
                                        ->with('idcosa', $id);
        
      case 'compromisos':
        $compromisos = Compromiso::where('id_lider', '=', $id)
                                 ->orderBy('updated_at', 'desc')
                                 ->paginate($rows);
        $totRows = Compromiso::where('id_lider', '=', $id)->count();
        $totPags = ceil($totRows/$rows);
        return view('pags.mapa.compromisos')->with('compromisos', $compromisos)
                                            ->with('totPags', $totPags)
                                            ->with('totRows', $totRows)
                                            ->with('rows', $rows)
                                            ->with('page', $page);

      default:
        # code...
        break;
    }
  }

  public function busquedaSVG (Request $request) {
    $texto = $request->get('palabra');
    $municipios = Municipio::where('nombre', 'LIKE', '%'.$texto.'%')
                           ->orderBy('nombre', 'asc')
                           ->get();
    return view('pags.mapa.busqSVG')->with('cosas', $municipios);
  }

  public function resumen (Request $request) {
    if($request->has('idcorp')) {
      $idcorpOrig = $request->get('idcorp');
    } else {
      $idcorpOrig = Corporacion::first()->id;
    }

    $idcorp = DB::connection()->getPdo()->quote($idcorpOrig);

    $blah = Corporacion::where('id', '=', $idcorpOrig)
                       ->first();
    $corp = $blah->nombre;

    $anios = FilaElectoral::select('anio')
                          ->where('id_corporacion', '=', $idcorpOrig)
                          ->groupBy('anio')
                          ->orderBy('anio', 'desc')->get();

    if($request->has('anio')) {
      $anioOrig = $request->get('anio');
      $anio = DB::connection()->getPdo()->quote($anioOrig);
    } else {
      $anio = $anios[0]->anio;
      $anioOrig = $anio;
    }

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
      $subregion->potencialelectoral = ($resumenfilasElec->potencialelectoral) ? $resumenfilasElec->potencialelectoral : 0;
      $subregion->votosestimados     = ($resumenVotosEsti->votosestimados) ? $resumenVotosEsti->votosestimados : 0;
    }

    $corporaciones = Corporacion::get();

    return view('pags.mapa.subregiones')->with('subregiones', $subregiones)
                                        ->with('anio', $anioOrig)
                                        ->with('anios', $anios)
                                        ->with('idcorp', $idcorpOrig)
                                        ->with('corp', $corp)
                                        ->with('corporaciones', $corporaciones);
  }

  public function poblacion (Request $request) {
    $municipio = Municipio::find($request->input('id'));
    var_dump($request->input('id'));
    $municipio->poblacion = $request->input('poblacion');

    $municipio->save();

    return redirect($request->input('ruta'));
  }

  public function subregion ($id, Request $request) {
    if ($id > 10 || $id < 1) {
      return redirect('/Mapa/Ant');
    }
    
    $subregion = Subregion::find($id);

    $subregiones = Subregion::select('id','nombre')->get();
    
    $subregion->poblacion = 0;
    foreach ($subregion->municipios as $municipio) {
      $subregion->poblacion = $subregion->poblacion + $municipio->poblacion;
    }

    $municipios = Municipio::select('id', 'nombre')
                           ->where('id_subregion', '=', $id)
                           ->get();
    
    return view('pags.mapaSubs')->with('subregion', $subregion)
                                ->with('idsub', $id)
                                ->with('municipios', $municipios)
                                ->with('subregiones', json_encode($subregiones));
  }
}
