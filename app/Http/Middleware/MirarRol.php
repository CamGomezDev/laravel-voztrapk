<?php

namespace App\Http\Middleware;

use Closure;

class MirarRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, ...$roles)
    {
      $tieneRol = false;
      foreach ($roles as $rol) {
        if(!$request->user()->tieneRol($rol) && !$tieneRol) {
          $tieneRol = false;
        } else {
          $tieneRol = true;
        }
      }

      if ($tieneRol) {
        return $next($request);
      }

      return redirect($request->user()->entrada());
    }
}
