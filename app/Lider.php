<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lider extends Model
{
  public function municipio() {
    return $this->belongsTo('App\Municipio', 'id_municipio', 'id');
  }

  public function comuna() {
    return $this->belongsTo('App\Comuna', 'id_comuna', 'id');
  }

  public function compromisos() {
    return $this->hasMany('App\Compromiso');
  }

  public function puesto_votacion() {
    return $this->belongsTo('App\PuestoVotacion', 'puesto_votacion_id', 'id');
  }
}
