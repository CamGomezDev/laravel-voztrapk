@foreach($municipios as $municipio)
  <li><a href="../Mapa?m={{$municipio->id}}" onmouseover="resaltarMun({{$municipio->id}})" onmouseout="atenuarMun({{$municipio->id}})">{{$municipio->nombre}}</a></li>
@endforeach