<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lider;
use App\Municipio;
use App\Comuna;
use App\PuestoVotacion;
use DB;

class LideresController extends AdministracionController
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
      $secNotNull  = 'id_comuna';
    } else {
      $secWhereHas = 'municipio';
      $secNotNull  = 'id_municipio';
    }

    if ($request->has('q')) {
      $q = $request->get('q');
      $lideres = Lider::whereHas($secWhereHas, function ($query) use ($q) {
                          $query->where('nombre', 'LIKE', '%'.$q.'%');
                        })
                      ->orwhere('nombre', 'LIKE', '%'.$q.'%')
                      ->orwhere('cedula', 'LIKE', '%'.$q.'%')
                      ->orwhere('correo', 'LIKE', '%'.$q.'%')
                      ->orwhere('telefono', 'LIKE', '%'.$q.'%')
                      ->orwhere('nivel', 'LIKE', '%'.$q.'%')
                      ->orwhere('tipolider', 'LIKE', '%'.$q.'%')
                      ->orwhere('activo', 'LIKE', '%'.$q.'%')
                      ->orwhere('votosestimados', '=', $q)
                      ->orderBy('nombre')->paginate($rows);
      
      
      $totRows = Lider::whereHas($secWhereHas, function ($query) use ($q) {
                          $query->where('nombre', 'LIKE', '%'.$q.'%');
                        })
                      ->orwhere('nombre', 'LIKE', '%'.$q.'%')
                      ->orwhere('cedula', 'LIKE', '%'.$q.'%')
                      ->orwhere('correo', 'LIKE', '%'.$q.'%')
                      ->orwhere('telefono', 'LIKE', '%'.$q.'%')
                      ->orwhere('nivel', 'LIKE', '%'.$q.'%')
                      ->orwhere('tipolider', 'LIKE', '%'.$q.'%')
                      ->orwhere('activo', 'LIKE', '%'.$q.'%')
                      ->orwhere('votosestimados', '=', $q)
                      ->orderBy('nombre')->count();
    } else {
      $lideres = Lider::whereNotNull($secNotNull)->orderBy('liders.nombre')->paginate($rows);
      $totRows = Lider::whereNotNull($secNotNull)->count();
    }
    // dd($filasElectorales);
    if ($sec == 'Med') {
      $seclista = Comuna::orderBy('id', 'asc')->pluck('nombre', 'id');
    } else {
      $seclista = Municipio::orderBy('nombre', 'asc')->pluck('nombre', 'id');
    }
    if ($sec == 'Med') {
      $puestosvotacion = PuestoVotacion::orderBy('nombre')->pluck('nombre', 'id');
    } else {
      $puestosvotacion = null;
    }

    return view('pags.administracion.lideres')->with('lideres', $lideres)
                                              ->with('seclista', $seclista)
                                              ->with('puestosvotacion', $puestosvotacion)
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
    $this->validate($request, [
      'nombre' => 'required',
      'id_municipio' => 'required'
    ]);

    $lider = new Lider;
    $lider->nombre         = $request->input('nombre');
    $lider->cedula         = $request->input('cedula');
    $lider->correo         = $request->input('correo');
    $lider->telefono       = $request->input('telefono');
    $lider->nivel          = $request->input('nivel');
    $lider->tipolider      = $request->input('tipolider');
    $lider->activo         = $request->input('activo');
    $lider->votosestimados = $request->input('votosestimados');
    if ($sec == 'Med') {
      $lider->id_comuna = $request->input('id_municipio');
    } else {
      $lider->id_municipio = $request->input('id_municipio');
    }
    if ($sec == 'Med') {
      $lider->puesto_votacion_id = $request->input('puesto');
    }

    $lider->save();

    return redirect('/Administracion/'.$sec.'/Lideres')->with('success', 'Líder creado');
  }

  /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function update($sec, Request $request)
  {
    $this->validate($request, [
      'nombre' => 'required',
      'id_municipio' => 'required'
    ]);

    $lider = Lider::find($request->input('id'));

    $lider->nombre         = $request->input('nombre');
    $lider->cedula         = $request->input('cedula');
    $lider->correo         = $request->input('correo');
    $lider->telefono       = $request->input('telefono');
    $lider->nivel          = $request->input('nivel');
    $lider->tipolider      = $request->input('tipolider');
    $lider->activo         = $request->input('activo');
    $lider->votosestimados = $request->input('votosestimados');
    $lider->id_municipio   = $request->input('id_municipio');
    if ($sec == 'Med') {
      $lider->id_comuna = $request->input('id_municipio');
    } else {
      $lider->id_municipio = $request->input('id_municipio');
    }
    if ($sec == 'Med') {
      $lider->puesto_votacion_id = $request->input('puesto');
    }

    $lider->save();

    return redirect('/Administracion/'.$sec.'/Lideres')->with('success', 'Líder actualizado');
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy(Request $request)
  {
    $lider = Lider::find($request->input('id'));

    if ($lider->has('compromisos')) {
      return redirect($request->input('ruta'))->with('error', 'Este líder tiene compromisos asignados. No se podrá eliminar si no están removidos.');
    } else {
      $lider->delete();
      return redirect($request->input('ruta'))->with('success', 'Líder eliminado');
    }
  }
}
