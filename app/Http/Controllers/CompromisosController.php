<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Compromiso;
use App\Lider;
use DB;

class CompromisosController extends AdministracionController
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
      $secNotNull  = 'id_comuna';
    } else {
      $secNotNull  = 'id_municipio';
    }

    if ($request->has('q')) {
      $q = $request->get('q');
      $compromisos = Compromiso::whereHas('lider', function ($query) use ($q,$secNotNull) {
                                   $query->where('nombre', 'LIKE', '%'.$q.'%')
                                         ->whereNotNull($secNotNull);
                                 })
                               ->orwhere('nombre', 'LIKE', '%'.$q.'%')
                               ->orwhere('descripcion', 'LIKE', '%'.$q.'%')
                               ->orwhere('cumplimiento', 'LIKE', '%'.$q.'%')
                               ->orwhere('costo', '=', $q)
                               ->join('lider', 'compromisos.id_lider', '=', 'liders.id')
                               ->orderBy('liders.nombre')->paginate($rows);
      
      $totRows = Compromiso::whereHas('lider', function ($query) use ($q) {
                               $query->where('nombre', 'LIKE', '%'.$q.'%')
                                     ->whereNotNull($secNotNull);
                             })
                           ->orwhere('nombre', 'LIKE', '%'.$q.'%')
                           ->orwhere('descripcion', 'LIKE', '%'.$q.'%')
                           ->orwhere('cumplimiento', 'LIKE', '%'.$q.'%')
                           ->orwhere('costo', '=', $q)->count();
    } else {
      $compromisos = Compromiso::whereHas('lider', function ($query) use ($secNotNull) {
                                   $query->whereNotNull($secNotNull);
                                 })
                               ->join('liders', 'compromisos.id_lider', '=', 'liders.id')
                               ->select('compromisos.*')
                               ->orderBy('liders.nombre')->paginate($rows);
      $totRows = Compromiso::whereHas('lider', function ($query) use ($secNotNull) {
                               $query->whereNotNull($secNotNull);
                             })->count();
    }
    // dd($filasElectorales);
    $lideres = Lider::whereNotNull($secNotNull)->orderBy('nombre', 'asc')->pluck('nombre', 'id');
    return view('pags.administracion.compromisos')->with('compromisos', $compromisos)
                                                  ->with('lideres', $lideres)
                                                  ->with('rows', $rows)
                                                  ->with('totRows', $totRows)
                                                  ->with('secNom', $secNom)
                                                  ->with('sec', $sec);
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
      'id_lider' => 'required',
      'nombre'  => 'required',
      'descripcion' => 'required',
      'cumplimiento' => 'required',
      'costo' => 'required',
    ]);

    $compromiso = new Compromiso;
    $compromiso->id_lider     = $request->input('id_lider');
    $compromiso->nombre       = $request->input('nombre');
    $compromiso->descripcion  = $request->input('descripcion');
    $compromiso->cumplimiento = $request->input('cumplimiento');
    $compromiso->costo        = $request->input('costo');

    $compromiso->save();

    return redirect('/Administracion/Compromisos')->with('success', 'Compromiso creado');
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
      'id_lider' => 'required',
      'nombre'  => 'required',
      'descripcion' => 'required',
      'cumplimiento' => 'required',
      'costo' => 'required',
    ]);

    $compromiso = Compromiso::find($request->input('id'));
    
    $compromiso->id_lider     = $request->input('id_lider');
    $compromiso->nombre       = $request->input('nombre');
    $compromiso->descripcion  = $request->input('descripcion');
    $compromiso->cumplimiento = $request->input('cumplimiento');
    $compromiso->costo        = $request->input('costo');

    $compromiso->save();

    return redirect($request->input('ruta'))->with('success', 'Compromiso actualizado');
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy(Request $request)
  {
    $compromiso = Compromiso::find($request->input('id'));
    $compromiso->delete();

    return redirect($request->input('ruta'))->with('success', 'Compromiso eliminado');
  }
}
