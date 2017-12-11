<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Comuna extends Model
{
    public function fila_electorals() {
        return $this->hasMany('App\FilaElectoral', 'id_comuna');
    }
    
    public function liders() {
        return $this->hasMany('App\Lider');
    }

    public function barrios() {
        return $this->hasMany('App\Barrio');
    }

    public function puesto_votacions() {
        return $this->hasMany('App\PuestoVotacion');
    }
}
