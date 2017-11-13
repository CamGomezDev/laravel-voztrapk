@foreach($cosas as $cosa)
  <li><a href="?{{(Request::segment(2) == 'Med') ? 'c' : 'm'}}={{$cosa->id}}" onmouseover="resaltarMun({{$cosa->id}})" onmouseout="atenuarMun({{$cosa->id}})">{{$cosa->nombre}}</a></li>
@endforeach