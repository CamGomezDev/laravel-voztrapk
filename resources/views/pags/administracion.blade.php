@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<div class="row"> 
		<div class="col-sm-3 col-md-2 sidebar">
			<div class="panel panel-default">
				<div class="panel-body">
				  <ul class="nav">
				    <li class="{{(Request::segment(2) == 'InfoElectoral') ? 'active-sidebar' : ''}}"><a href="/Administracion/InfoElectoral">Información Electoral</a></li>
				    <li class="{{(Request::segment(2) == 'Lideres') ? 'active-sidebar' : ''}}"><a href="/Administracion/Lideres">Líderes</a></li>
				    <li class="{{(Request::segment(2) == 'Compromisos') ? 'active-sidebar' : ''}}"><a href="/Administracion/Compromisos">Compromisos</a></li>
				    <li class="{{(Request::segment(2) == 'Corporaciones') ? 'active-sidebar' : ''}}"><a href="/Administracion/Corporaciones">Corporaciones</a></li>
            <li class="{{(Request::segment(2) == 'Visitas') ? 'active-sidebar' : ''}}"><a href="/Administracion/Visitas">Visitas</a></li>
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