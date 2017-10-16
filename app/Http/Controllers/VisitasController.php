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
                             ->orderBy('nombre')->paginate($rows);
      
      $totRows = Corporacion::where('nombre', 'LIKE', '%'.$q.'%')->count();
    } else {
      $municipios = Municipio::orderBy('nombre')->paginate($rows);
      $totRows    = Municipio::count();
    }

    foreach($municipios as $municipio) {
      $visitas = Visita::orderBy('llegada', 'desc')->where('id_municipio', '=', $municipio->id)->paginate(5);
      $municipio->visitas = 
    }

    // foreach ($municipios as $municipio) {
    //   $visitas = $visi
    // }
    // dd($filasElectorales);
    return view('pags.administracion.visitas')->with('municipios', $municipios)
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
      'nombre' => 'required'
    ]);

    $corporacion = new Corporacion;
    $corporacion->nombre = $request->input('nombre');

    $corporacion->save();

    return redirect('/Administracion/Corporaciones')->with('success', 'Corporación creada');
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

    $corporacion = Corporacion::find($request->input('id'));

    $corporacion->nombre = $request->input('nombre');

    $corporacion->save();

    return redirect($request->input('ruta'))->with('success', 'Corporación actualizada');
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy(Request $request)
  {
    $corporacion = Corporacion::find($request->input('id'));
    $corporacion->delete();

    return redirect($request->input('ruta'))->with('success', 'Compromiso eliminado');
  }
}
