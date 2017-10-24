<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Municipio;
use App\Visita;

class VisitasController extends AdministracionController
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
      $municipios = Municipio::where('nombre', 'LIKE', '%'.$q.'%')
                             ->has('visitas')
                             ->orderBy('nombre')->paginate($rows);
      $totRows = Municipio::where('nombre', 'LIKE', '%'.$q.'%')->count();
    } else {
      $municipios = Municipio::orderBy('nombre')
                             ->has('visitas')
                             ->paginate($rows);
      $totRows    = Municipio::has('visitas')->count();
    }

    $municipiosInput = Municipio::orderBy('nombre')->pluck('nombre', 'id');

    return view('pags.administracion.visitas')->with('municipios', $municipios)
                                              ->with('municipiosInput', $municipiosInput)
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
      'notas' => 'required',
      'llegada' => 'required',
      'salida' => 'required',
      'id_municipio' => 'required'
    ]);

    $visita = new Visita;
    $visita->notas         = $request->input('notas');
    $visita->llegada       = $request->input('llegada');
    $visita->salida        = $request->input('salida');
    $visita->id_municipio  = $request->input('id_municipio');

    $visita->save();

    return redirect('/Administracion/Visitas')->with('success', 'Visita añadida');
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
      'notas' => 'required',
      'llegada' => 'required',
      'salida' => 'required',
      'id_municipio' => 'required'
    ]);

    $visita = Visita::find($request->input('id'));
    $visita->notas         = $request->input('notas');
    $visita->llegada       = $request->input('llegada');
    $visita->salida        = $request->input('salida');
    $visita->id_municipio  = $request->input('id_municipio');

    $visita->save();

    return redirect($request->input('ruta'))->with('success', 'Visita actualizada');
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy(Request $request)
  {
    $visita = Visita::find($request->input('id'));
    $visita->delete();

    return redirect($request->input('ruta'))->with('success', 'Visita eliminada');
  }
}
