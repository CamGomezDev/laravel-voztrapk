@extends('layouts.app')

@section('content')

<div class="container-fluid">
	<div class="row"> 
		<div class="col-sm-3 col-md-2 sidebar">
			<div class="panel panel-default">
				<div class="panel-body" style="background: white;">
				  <ul class="nav nav-sidebar">
				    <li class="{{(Request::segment(2) == 'Usuarios') ? 'active-sidebar' : ''}}"><a href="/Ajustes/Usuarios">Usuarios</a></li>
				    <li class="{{(Request::segment(2) == 'Roles') ? 'active-sidebar' : ''}}"><a href="/Ajustes/Roles">Roles</a></li>
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