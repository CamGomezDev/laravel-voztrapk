<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Rol;
use DB;

class UsersController extends AjustesController
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
      // To avoid people deleting root and because i'm too lazy to implement it in search
      $usuarios = User::where('name', '!=', 'root')->orderBy('name', 'asc')->paginate($rows);
      $totRows  = User::where('name', '!=', 'root')->count();
      // $usuarios = User::where('name', '!=', 'root')
      //                 ->whereHas('rol', function ($query) use ($q) {
      //                     $query->where('nombre', 'LIKE', '%'.$q.'%');
      //                   })
      //                 ->orwhere('name', 'LIKE', '%'.$q.'%')
      //                 ->orwhere('email', 'LIKE', '%'.$q.'%')
      //                 ->orderBy('name', 'asc')->paginate($rows);
      
      
      // $totRows = User::where('name', '!=', 'root')
      //                ->whereHas('rol', function ($query) use ($q) {
      //                    $query->where('name', 'LIKE', '%'.$q.'%');
      //                  })
      //                ->orwhere('name', 'LIKE', '%'.$q.'%')
      //                ->orwhere('email', 'LIKE', '%'.$q.'%')->count();
    } else {
      $usuarios = User::where('name', '!=', 'root')->orderBy('name', 'asc')->paginate($rows);
      $totRows  = User::where('name', '!=', 'root')->count();
    }

    $roles = Rol::pluck('nombre', 'id');
    return view('pags.ajustes.usuarios')->with('usuarios', $usuarios)
                                        ->with('roles', $roles)
                                        ->with('rows', $rows)
                                        ->with('totRows', $totRows);
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
    if($request->input('password') != null) {
      $this->validate($request, [
        'id' => 'required',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'password' => 'string|min:6|confirmed',
        'id_rol' => 'required',
      ]);
    } else {
      $this->validate($request, [
        'id' => 'required',
        'name' => 'required|string|max:255',
        'email' => 'required|string|email|max:255',
        'id_rol' => 'required',
      ]);
    }
    
    $usuario = User::find($request->input('id'));

    $usuario->name   = $request->input('name');
    $usuario->email  = $request->input('email');
    $usuario->id_rol = $request->input('id_rol');

    if($request->input('password') != null) {
      $usuario->password = bcrypt($request->input('password'));
    }

    $usuario->save();

    return redirect($request->input('ruta'))->with('success', 'Usuario actualizado');
  }

  /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
  public function destroy(Request $request)
  {
    $usuario = User::find($request->input('id'));
    $usuario->delete();

    return redirect($request->input('ruta'))->with('success', 'Usuario eliminado');
  }
}
