<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compromiso extends Model
{
  public function lider() {
    return $this->belongsTo('App\Lider', 'id_lider', 'id');
  }
}
