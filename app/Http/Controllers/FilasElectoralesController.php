<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\FilaElectoral;
use App\Municipio;
use App\Corporacion;
use DB;

class FilasElectoralesController extends AdministracionController
{
  /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
  public function index(Request $request)
  {
    if ($request->has('rows')) {
      $rows = $request->get('rows');
    } else {
      $rows = 5;
    }

    if ($request->has('q')) {
      $q = $request->get('q');
      $filasElectorales = FilaElectoral::whereHas('municipio', function ($query) use ($q) {
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
                                       ->join('municipios', 'fila_electorals.id_municipio', '=', 'municipios.id')
                                       ->orderBy('municipios.nombre')
                                       ->orderBy('anio', 'desc')->paginate($rows);
      
      
      $totRows = FilaElectoral::whereHas('municipio', function ($query) use ($q) {
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
      $filasElectorales = FilaElectoral::join('municipios', 'fila_electorals.id_municipio', '=', 'municipios.id')
                                       ->select('fila_electorals.*', 'municipios.id as municipio_id')
                                       ->orderBy('municipios.nombre')
                                       ->orderBy('anio', 'desc')
                                       ->paginate($rows);
      $totRows = FilaElectoral::count();
    }
    // dd($filasElectorales);
    $municipios = Municipio::orderBy('nombre', 'asc')->pluck('nombre', 'id');
    $corporaciones = Corporacion::orderBy('nombre', 'asc')->pluck('nombre', 'id');
    return view('pags.administracion.infoelectoral')->with('filasElectorales', $filasElectorales)
                                                    ->with('municipios', $municipios)
                                                    ->with('corporaciones', $corporaciones)
                                                    ->with('rows', $rows)
                                                    ->with('totRows', $totRows);
  }

  /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
  public function store(Request $request)
  {
    $this->validate($request, [
      'id_municipio' => 'required',
      'id_corporacion'  => 'required',
      'anio' => 'required'
    ]);

    $filaElectoral = new FilaElectoral;
    $filaElectoral->id_municipio       = $request->input('id_municipio');
    $filaElectoral->id_corporacion     = $request->input('id_corporacion');
    $filaElectoral->votostotales       = $request->input('votostotales');
    $filaElectoral->votoscandidato     = $request->input('votoscandidato');
    $filaElectoral->votospartido       = $request->input('votospartido');
    $filaElectoral->potencialelectoral = $request->input('potencialelectoral');
    $filaElectoral->anio               = $request->input('anio');

    $filaElectoral->save();

    return redirect('/Administracion/InfoElectoral')->with('success', 'Fila electoral creada');
  }

  /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function update(Request $request)
  {
    $this->validate($request, [
      'id' => 'required',
      'id_municipio' => 'required',
      'id_corporacion'  => 'required',
      'anio' => 'required'
    ]);
    
    $filaElectoral = FilaElectoral::find($request->input('id'));

    $filaElectoral->id_municipio       = $request->input('id_municipio');
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
