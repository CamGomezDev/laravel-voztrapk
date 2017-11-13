<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Municipio extends Model
{
  public function fila_electorals() {
    return $this->hasMany('App\FilaElectoral', 'id_municipio');
  }

  public function liders() {
    return $this->hasMany('App\Lider');
  }

  public function visitas() {
    return $this->hasMany('App\Visita', 'id_municipio');
  }
}
