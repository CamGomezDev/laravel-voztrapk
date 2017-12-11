<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Barrio extends Model
{
    public function puesto_votacions() {
        return $this->hasMany('App\PuestoVotacion', 'barrio_id');
    }

    public function comuna() {
        return $this->belongsTo('App\Comuna', 'comuna_id', 'id');
    }
}
