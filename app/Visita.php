<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Visita extends Model
{
  public function municipio () {
    $this->belongsTo('App\Municipio', 'id_municipio', 'id');
  }
}
