<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministracionController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('rol:1,3');
  }
}
