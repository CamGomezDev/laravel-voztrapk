<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Rol;
use DB;

class RolesController extends AjustesController
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
      $roles = Rol::where('nombre', 'LIKE', '%'.$q.'%')
                  ->orWhere('descripcion', 'LIKE', '%'.$q.'%')
                  ->paginate($rows);
      
      $totRows = Rol::where('nombre', 'LIKE', '%'.$q.'%')
                    ->orWhere('descripcion', 'LIKE', '%'.$q.'%')->count();
    } else {
      $roles   = Rol::paginate($rows);
      $totRows = Rol::count();
    }

    return view('pags.ajustes.roles')->with('roles', $roles)
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
      'descripcion' => 'required'
    ]);

    $rol = new Rol;
    $rol->nombre      = $request->input('nombre');
    $rol->descripcion = $request->input('descripcion');

    $rol->save();

    return redirect('/Ajustes/Roles')->with('success', 'Rol creado');
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
      'descripcion' => 'required'
    ]);

    $rol = Rol::find($request->input('id'));

    $rol->nombre      = $request->input('nombre');
    $rol->descripcion = $request->input('descripcion');

    $rol->save();

    return redirect($request->input('ruta'))->with('success', 'Rol actualizado');
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy(Request $request)
  {
    $rol = Rol::find($request->input('id'));
    $rol->delete();

    return redirect($request->input('ruta'))->with('success', 'Rol eliminado');
  }
}
