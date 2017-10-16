<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  use Notifiable;

  /**
    * The attributes that are mass assignable.
    *
    * @var array
    */
  protected $fillable = [
      'name', 'email', 'password', 'id_rol',
  ];

  /**
    * The attributes that should be hidden for arrays.
    *
    * @var array
    */
  protected $hidden = [
      'password', 'remember_token',
  ];


  public function rol () {
    return $this->belongsTo('App\Rol', 'id_rol', 'id');
  }

  public function tieneRol ($rol) {
    if ($this->id_rol == $rol) {
      return true;
    }
    return false;
  }

  public function entrada () {
    $id = $this->id_rol;
    if ($id == 1 || $id == 3 || $id == 4) {
      return '/Mapa';
    }
    if ($id == 2) {
      return '/Ajustes';
    }
  }
}
