<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PuestoVotacion extends Model
{
    public function barrio() {
        return $this->belongsTo('App\Barrio', 'barrio_id', 'id');
    }

    public function comuna() {
        return $this->belongsTo('App\Comuna', 'comuna_id', 'id');
    }

    public function liders() {
        return $this->hasMany('App\Lider');
    }
}
