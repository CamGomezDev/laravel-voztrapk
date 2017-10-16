<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Lider;
use App\Municipio;
use DB;

class LideresController extends AdministracionController
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
      $lideres = Lider::whereHas('municipio', function ($query) use ($q) {
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
      
      
      $totRows = Lider::whereHas('municipio', function ($query) use ($q) {
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
      $lideres = Lider::orderBy('liders.nombre')->paginate($rows);
      $totRows = Lider::count();
    }
    // dd($filasElectorales);
    $municipios = Municipio::orderBy('nombre', 'asc')->pluck('nombre', 'id');
    return view('pags.administracion.lideres')->with('lideres', $lideres)
                                              ->with('municipios', $municipios)
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
    $lider->id_municipio   = $request->input('id_municipio');

    $lider->save();

    return redirect('/Administracion/Lideres')->with('success', 'Líder creado');
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

    $lider->save();

    return redirect('/Administracion/Lideres')->with('success', 'Líder actualizado');
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
    $lider->delete();

    return redirect($request->input('ruta'))->with('success', 'Líder eliminado');
  }
}
