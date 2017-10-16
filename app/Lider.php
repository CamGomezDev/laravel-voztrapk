<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Lider extends Model
{
  public function municipio() {
    return $this->belongsTo('App\Municipio', 'id_municipio', 'id');
  }

  public function compromisos() {
    return $this->hasMany('App\Compromiso');
  }
}
