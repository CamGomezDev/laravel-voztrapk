<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Corporacion;
use DB;

class CorporacionesController extends AdministracionController
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

    if ($request->has('q')) {
      $q = $request->get('q');
      $corporaciones = Corporacion::where('nombre', 'LIKE', '%'.$q.'%')
                                  ->orderBy('nombre')->paginate($rows);
      
      $totRows = Corporacion::where('nombre', 'LIKE', '%'.$q.'%')->count();
    } else {
      $corporaciones = Corporacion::orderBy('nombre')->paginate($rows);
      $totRows = Corporacion::count();
    }
    // dd($filasElectorales);
    return view('pags.administracion.corporaciones')->with('corporaciones', $corporaciones)
                                                    ->with('rows', $rows)
                                                    ->with('totRows', $totRows)
                                                    ->with('secNom', 'Todos')
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
    if ($corporacion->has('fila_electorals')) {
      return redirect($request->input('ruta'))->with('error', 'Esta corporación tiene filas electorales asignadas. No se podrá eliminar hasta que estén removidas.');
    }
    $corporacion->delete();

    return redirect($request->input('ruta'))->with('success', 'Corporación eliminada');
  }
}
