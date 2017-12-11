<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Municipio;
use App\Comuna;
use App\Subregion;
use App\FilaElectoral;
use App\Lider;
use App\Compromiso;
use App\Corporacion;
use DB;

class MapaMedController extends Controller
{
  public function __construct () {
    $this->middleware('auth');
    $this->middleware('rol:1,3,4');
  }

  public function index (Request $request) {
    if ($request->has('c')) {
      $id = $request->get('c');
      $comunaAc = Comuna::find($id);
    } else {
      $id = false;
      $comunaAc = null;
    }

    $comunas = Comuna::select('id', 'nombre')->get();

    foreach ($comunas as $comuna) {
      if (count($comuna->fila_electorals)) {
        $comuna->panel = 1;
        $info = FilaElectoral::select('votospartido', 'votoscandidato')
                             ->where('id_comuna', '=', $comuna->id)
                             ->orderBy('anio', 'desc')
                             ->first();
        $comuna->votospartido   = $info->votospartido;
        $comuna->votoscandidato = $info->votoscandidato;
      } else {
        $comuna->panel = 0;
      }
      unset($comuna->fila_electorals);
    }

    return view('pags.mapaMed')->with('comunas', json_encode($comunas))
                               ->with('comuna', $comunaAc)
                               ->with('idcom', $id);
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
      $comuna = Comuna::where('id', '=', $id)
                      ->first();
      
      $cosafrase = $comuna->nombre;
    }


    switch ($ejecutar) {
      case 'filasElectorales':
        $filasElectorales = FilaElectoral::where('id_comuna', '=', $id)
                                         ->orderBy('anio', 'desc')
                                         ->orderBy('id_corporacion', 'desc')
                                         ->paginate($rows);
        $totRows = FilaElectoral::where('id_comuna', '=', $id)->count();
        $totPags = ceil($totRows/$rows);
        return view('pags.mapa.filaselectorales')->with('filasElectorales', $filasElectorales)
                                                 ->with('cosa', $comuna)
                                                 ->with('cosafrase', $cosafrase)
                                                 ->with('editar', false)
                                                 ->with('totPags', $totPags)
                                                 ->with('totRows', $totRows)
                                                 ->with('rows', $rows)
                                                 ->with('page', $page)
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
                          ->where('id_comuna', '=', $id)
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
                          ->where('id_comuna', '=', $id)
                          ->count();
        } else {
          $lideres = Lider::where('id_comuna', '=', $id)
                          ->orderBy('nombre', 'asc')
                          ->paginate($rows);
          $totRows = Lider::where('id_comuna', '=', $id)->count();
        }
        $votosestimados = DB::table('liders')->where('id_municipio', '=', $id)->sum('votosestimados');
        $totPags = ceil($totRows/$rows);
        return view('pags.mapa.lideres')->with('lideres', $lideres)
                                        ->with('cosa', $comuna->nombre . " - Votos estimados totales: ".$votosestimados)
                                        ->with('totPags', $totPags)
                                        ->with('totRows', $totRows)
                                        ->with('idcosa', $id)
                                        ->with('rows', $rows)
                                        ->with('page', $page);
        
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
    $comunas = Comuna::where('nombre', 'LIKE', '%'.$texto.'%')
                     ->orderBy('nombre', 'asc')
                     ->get();
    return view('pags.mapa.busqSVG')->with('cosas', $comunas);
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
      $comuna->potencialelectoral = ($resumenfilasElec->potencialelectoral) ? $resumenfilasElec->potencialelectoral : 0;
      $comuna->votosestimados     = ($resumenVotosEsti->votosestimados) ? $resumenVotosEsti->votosestimados : 0;
    }

    $corporaciones = Corporacion::get();

    return view('pags.mapa.comunas')->with('comunas', $comunas)
                                    ->with('anio', $anioOrig)
                                    ->with('anios', $anios)
                                    ->with('idcorp', $idcorpOrig)
                                    ->with('corp', $corp)
                                    ->with('corporaciones', $corporaciones);
  }

  public function comuna (Request $request) {
    $comuna = Comuna::find($request->input('id'));

    $comuna->puestos = $request->input('puestos');
    $comuna->barrios = $request->input('barrios');
    $comuna->mesas   = $request->input('mesas');

    $comuna->save();

    return redirect($request->input('ruta'));
  }
}
