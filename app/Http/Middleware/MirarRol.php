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

    // Recibe como argumento los roles permitidos, y revisar si el 
    // usuario tiene alguno de ellos.
    public function handle($request, Closure $next, ...$roles)
    {
      // $tieneRol está definida en la clase User
      if (!$request->user()->tieneRol($roles)) {
        return redirect($request->user()->entrada());
      }
      return $next($request);
    }
}
