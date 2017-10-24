<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Subregion;

class SubregionesController extends Controller
{
  public function index ($id) {
    $subregion = Subregion::find($id);

    return view('pags.mapa')->with('idmun', 0);
  }
}
