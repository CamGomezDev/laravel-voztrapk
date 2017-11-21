@extends('layouts.app')

@section('content')
<div class="container-fluid">
	<div class="well well-sm" style="border: 1px solid rgb(190, 190, 190); text-align:center">
		{{$secNom}}
	</div>
	<div class="row">
		<div class="col-sm-3 col-md-2 sidebar">
			<div class="panel panel-default">
				<div class="panel-body">
				  <ul class="nav">
				    <li class="{{(Request::segment(3) == 'InfoElectoral') ? 'active-sidebar' : ''}}"><a href="/Administracion/{{$sec}}/InfoElectoral">Información Electoral</a></li>
				    <li class="{{(Request::segment(3) == 'Lideres') ? 'active-sidebar' : ''}}"><a href="/Administracion/{{$sec}}/Lideres">Líderes</a></li>
				    <li class="{{(Request::segment(3) == 'Compromisos') ? 'active-sidebar' : ''}}"><a href="/Administracion/{{$sec}}/Compromisos">Compromisos</a></li>
				    <li class="{{(Request::segment(3) == 'Corporaciones') ? 'active-sidebar' : ''}}"><a href="/Administracion/{{$sec}}/Corporaciones">Corporaciones</a></li>
            <li class="{{(Request::segment(3) == 'Visitas') ? 'active-sidebar' : ''}}"><a href="/Administracion/{{$sec}}/Visitas">Visitas</a></li>
				  </ul>
				</div>
			</div>
		</div>
		<div class="col-sm-9 col-md-10">
      @include('inc.mensajes')
		  @yield('seccion')
		</div>
	</div>
</div>
@endsection