<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PuestoVotacion;
use App\Comuna;
use App\Barrio;
use DB;

class PuestosVotacionController extends AdministracionController
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

    if ($request->has('c')) {
      $id = $request->get('c');
      if ($id > 0 && $id < 17) {
        $puestosvotacion = PuestoVotacion::where('comuna_id', '=', $id)
                                         ->orderBy('nombre')->paginate($rows);
        $totRows = PuestoVotacion::where('comuna_id', '=', $id)->count();
        $comuna = Comuna::where('id', '=', $id)->first();
      } else {
        $puestosvotacion = PuestoVotacion::orderBy('comuna_id')->orderBy('nombre')->paginate($rows);
        $comuna = null;
        $totRows = PuestoVotaccion::count();
      }
    } else {
      $puestosvotacion = PuestoVotacion::orderBy('comuna_id')->orderBy('nombre')->paginate($rows);
      $comuna = null;
      $totRows = PuestoVotacion::count();
    }
    $comunas = Comuna::pluck('nombre', 'id');
    $barrios = Barrio::orderBy('nombre')->pluck('nombre', 'id');

    return view('pags.administracion.puestosvotacion')->with('comunas', $comunas)
                                                      ->with('barrios', $barrios)
                                                      ->with('comuna', $comuna)
                                                      ->with('puestosvotacion', $puestosvotacion)
                                                      ->with('rows', $rows)
                                                      ->with('totRows', $totRows)
                                                      ->with('secNom', 'Medellín')
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
      'nombre' => 'required'
    ]);

    $idcom = $request->input('comuna');

    $puesto = new PuestoVotacion;

    $puesto->comuna_id = $idcom;
    $puesto->nombre    = $request->input('nombre');
    $puesto->mesas     = $request->input('mesas');
    $puesto->barrio_id = $request->input('barrio');
    $puesto->save();

    return redirect('/Administracion/Med/PuestosVotacion?c='.$idcom)->with('success', 'Puesto de votación añadido');
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
      'nombre' => 'required'
    ]);
    $puesto = PuestoVotacion::find($request->input('id'));

    $puesto->comuna_id = $request->input('comuna');
    $puesto->barrio_id = $request->input('barrio');
    $puesto->nombre    = $request->input('nombre');
    $puesto->mesas     = $request->input('mesas');
    $puesto->save();

    return redirect($request->input('ruta'))->with('success', 'Puesto de votación actualizado');
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy(Request $request)
  {
    $puesto = PuestoVotacion::find($request->input('id'));
    $puesto->delete();

    return redirect($request->input('ruta'))->with('success', 'Puesto de votación eliminado');
  }
}
