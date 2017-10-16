<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Corporacion extends Model
{
  public function fila_electorals () {
    return hasMany('App\FilaElectoral');
  }
}
