<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FilaElectoral;
use App\Municipio;
use App\Corporacion;
use App\Comuna;
use DB;

class FilasElectoralesController extends AdministracionController
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
  public function index($sec, Request $request)
  {
    if ($request->has('rows')) {
      $rows = $request->get('rows');
    } else {
      $rows = 5;
    }

    $secNom = ($sec == 'Med') ? 'Medellín' : 'Antioquia';

    if ($sec == 'Med') {
      $secWhereHas = 'comuna';
      $secTabla    = 'comunas';
      $fila_secTab = 'fila_electorals.id_comuna';
      $secId       = 'comunas.id';
      $secOrderBy  = 'comunas.id';
      $secIdAsId   = 'comunas.id as comuna_id';
      $secNotNull  = 'id_comuna';
    } else {
      $secWhereHas = 'municipio';
      $secTabla    = 'municipios';
      $fila_secTab = 'fila_electorals.id_municipio';
      $secId       = 'municipios.id';
      $secOrderBy  = 'municipios.nombre';
      $secIdAsId   = 'municipios.id as municipio_id';
      $secNotNull  = 'id_municipio';
    }

    if ($request->has('q')) {
      $q = $request->get('q');
      $filasElectorales = FilaElectoral::whereHas($secWhereHas, function ($query) use ($q) {
                                           $query->where('nombre', 'LIKE', '%'.$q.'%');
                                         })
                                       ->orWhereHas('corporacion', function ($query) use ($q) {
                                           $query->where('nombre', 'LIKE', '%'.$q.'%');
                                         })
                                       ->orwhere('votostotales', '=', $q)
                                       ->orwhere('votoscandidato', '=', $q)
                                       ->orwhere('votospartido', '=', $q)
                                       ->orwhere('potencialelectoral', '=', $q)
                                       ->orwhere('anio', '=', $q)
                                       ->join($secTabla, $fila_secTab, '=', $secId)
                                       ->orderBy($secOrderBy)
                                       ->orderBy('anio', 'desc')->paginate($rows);
      
      
      $totRows = FilaElectoral::whereHas($secWhereHas, function ($query) use ($q) {
                                  $query->where('nombre', 'LIKE', '%'.$q.'%');
                                })
                              ->orWhereHas('corporacion', function ($query) use ($q) {
                                  $query->where('nombre', 'LIKE', '%'.$q.'%');
                                })
                              ->orwhere('votostotales', '=', $q)
                              ->orwhere('votoscandidato', '=', $q)
                              ->orwhere('votospartido', '=', $q)
                              ->orwhere('potencialelectoral', '=', $q)
                              ->orwhere('anio', '=', $q)->count();
    } else {
      $filasElectorales = FilaElectoral::join($secTabla, $fila_secTab, '=', $secId)
                                       ->select('fila_electorals.*', $secIdAsId)
                                       ->orderBy($secOrderBy)
                                       ->orderBy('anio', 'desc')
                                       ->paginate($rows);
      $totRows = FilaElectoral::whereNotNull($secNotNull)->count();
    }
    // dd($filasElectorales);
    if ($sec == 'Med') {
      $seclista = Comuna::orderBy('id', 'asc')->pluck('nombre', 'id');
    } else {
      $seclista = Municipio::orderBy('nombre', 'asc')->pluck('nombre', 'id');
    }

    $corporaciones = Corporacion::orderBy('nombre', 'asc')->pluck('nombre', 'id');

    return view('pags.administracion.infoelectoral')->with('filasElectorales', $filasElectorales)
                                                    ->with('seclista', $seclista)
                                                    ->with('corporaciones', $corporaciones)
                                                    ->with('rows', $rows)
                                                    ->with('totRows', $totRows)
                                                    ->with('sec', $sec)
                                                    ->with('secNom', $secNom);
  }

  /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
  public function store($sec, Request $request)
  {
    $filaElectoral = new FilaElectoral;

    if ($sec == 'Med') {
      $filaElectoral->id_comuna = $request->input('id_municipio');
    } else {
      $filaElectoral->id_municipio = $request->input('id_municipio');
    }
    $filaElectoral->id_corporacion     = $request->input('id_corporacion');
    $filaElectoral->votostotales       = $request->input('votostotales');
    $filaElectoral->votoscandidato     = $request->input('votoscandidato');
    $filaElectoral->votospartido       = $request->input('votospartido');
    $filaElectoral->potencialelectoral = $request->input('potencialelectoral');
    $filaElectoral->anio               = $request->input('anio');

    $filaElectoral->save();

    return redirect('/Administracion/'.$sec.'/InfoElectoral')->with('success', 'Fila electoral creada');
  }

  /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function update(Request $request, $sec)
  {
    $filaElectoral = FilaElectoral::find($request->input('id'));

    if ($sec == 'Med') {
      $filaElectoral->id_comuna = $request->input('id_municipio');
    } else {
      $filaElectoral->id_municipio = $request->input('id_municipio');
    }
    $filaElectoral->id_corporacion     = $request->input('id_corporacion');
    $filaElectoral->votostotales       = $request->input('votostotales');
    $filaElectoral->votoscandidato     = $request->input('votoscandidato');
    $filaElectoral->votospartido       = $request->input('votospartido');
    $filaElectoral->potencialelectoral = $request->input('potencialelectoral');
    $filaElectoral->anio               = $request->input('anio');

    $filaElectoral->save();

    return redirect($request->input('ruta'))->with('success', 'Fila electoral actualizada');
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy(Request $request)
  {
    $filaElectoral = FilaElectoral::find($request->input('id'));
    $filaElectoral->delete();

    return redirect($request->input('ruta'))->with('success', 'Fila electoral eliminada');
  }
}
