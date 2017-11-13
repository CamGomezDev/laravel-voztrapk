<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subregion extends Model
{
    public function liders() {
        return $this->hasManyThrough('App\Lider', 'App\Municipio', 'id_subregion', 'id_municipio');
    }

    public function municipios() {
        return $this->hasMany('App\Municipio', 'id_subregion');
    }
}
