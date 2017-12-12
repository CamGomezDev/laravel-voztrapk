@extends('pags.administracion')
@section('seccion')
<script type="text/javascript" src="{{asset('js/puestosvotacion.js')}}"></script>
<div class="panel panel-default">
	<div class="panel-heading" id="head">
		<h4>Puestos de votación</h4>
	</div>
	<div class="panel-body">
    <?php $import = false; $alt = false; $panelsup = ['PuestosVotacion','PuestosVotacion','puestos','Puesto de Votación']; ?>
    <div class="row">
      <div class="pull-left" style="margin-left: 15px;">
        @if(Request::segment(1) != 'Ajustes')
        {!!Form::open(['action' => 'ExportExcelController@'.$panelsup[2].(($alt) ? Request::segment(2) : ''), 'method' => 'GET', 'class' => 'pull-left', 'style' => 'margin-bottom: 5px; margin-right: 5px;'])!!}
          <button type="submit" class="btn btn-custom" style="padding-right:7px; padding-left:7px"><i class="fa fa-download fa-lg" aria-hidden="true"></i></button>
        {!!Form::close()!!}
        @endif
        <p class="pull-right">
          <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#ModalCrear"><i class="fa fa-plus" aria-hidden="true"></i> {{$panelsup[3]}}</button>
        </p>
      </div>
      <span class="btn-group dropdown pull-right" style="position: relative; display: inline-block; vertical-align: middle; margin-right: 15px">
        <?php $idcom = (isset($_GET['c']) ? $_GET['c'] : 0) ?>
        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <h4 style="margin: 0px">{{($idcom) ? $comuna->nombre : 'Todas las comunas'}} <span class="caret"></span></h4>
        </button>
        <ul class="dropdown-menu" style="margin: 0px; padding: 0px; border-radius: 2px">
        @if($idcom)
        <li>
          <a href='{{Request::url()}}' style="margin: 3px; padding: 3px;">
            <h4 style="margin: 0px; padding: 0px">Todas las comunas</h4>
          </a>
        </li>
        @endif
        @foreach($comunas as $comid => $nombre)
          @if($comid != $idcom)
          <li>
            <a href='?c={{$comid}}' style="margin: 3px; padding: 3px;" onclick="">
              <h4 style="margin: 0px; padding: 0px">
                {{$nombre}}
              </h4>
            </a>
          </li>
          @endif
        @endforeach
        </ul>
      </span>
    </div>

    <div class="table-responsive">
      <table class="table table-striped table-bordered" style="margin-bottom: 0px">
        <thead>
          <tr>
            @if(!$idcom)
            <th>Comuna</th>
            @endif
            <th>Barrio</th>
            <th>Nombre</th>
            <th>Mesas</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        @if($totRows)
          @foreach($puestosvotacion as $puestovotacion)
          <tr>
            @if(!$idcom)
            <td>{{$puestovotacion->comuna_id}}</td>
            @endif
            <td>{{$puestovotacion->barrio->nombre}}</td>
            <td>{{$puestovotacion->nombre}}</td>
            <td>{{$puestovotacion->mesas}}</td>
            <td style="text-align: center">
              <h4 style="margin: 0;">
                <a type="button" data-toggle="modal" data-target="#ModalActualizar"
                  data-id=    "{{$puestovotacion->id}}"
                  data-comuna="{{$puestovotacion->comuna_id}}"
                  data-barrio="{{$puestovotacion->barrio_id}}"
                  data-nombre="{{$puestovotacion->nombre}}"
                  data-mesas ="{{$puestovotacion->mesas}}"><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right: 10px"></i></a>
                <a type="button" data-toggle="modal" data-target="#ModalEliminar" data-id="{{$puestovotacion->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
              </h4>
            </td>
          </tr>
          @endforeach
        @elseif($idcom)
          <tr>
            <td colspan=4>No se encontraron resultados para {{$comuna->nombre}}</td>
          </tr>
        @else
          <tr>
            <td colspan=5>No se encontraron resultados</td>
          </tr>
        @endif
        </tbody>
      </table>
    </div>
    @if($idcom)
    <?php
    $queryString = [];
    if (isset($_GET['q'])) {
      $queryString['q'] = $_GET['q'];
    } if (isset($_GET['rows'])) {
      $queryString['rows'] = $_GET['rows'];
    } if (isset($_GET['page'])) {
      $queryString['page'] = $_GET['page'];
    } if (isset($_GET['c'])) {
      $queryString['c'] = $_GET['c'];
    }
    ?>
    {{$puestosvotacion->appends($queryString)->links()}}
    @include('inc.filas')
    @endif
  </div>
</div>

<!-- Modal - Crear -->
<div class="modal fade" id="ModalCrear" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Añadir Puesto de Votación</h4>
      </div>
      {!!Form::open(['action' => 'PuestosVotacionController@store', 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::label('', 'Comuna')}}
            {{Form::select('comuna', $comunas, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar comuna...', 'required'])}}
            {{Form::label('', 'Barrio')}}
            {{Form::select('barrio', $barrios, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar barrio...', 'required'])}}
            {{Form::label('', 'Nombre')}}
            {{Form::text('nombre', '', ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Número de mesas')}}
            {{Form::number('mesas', '', ['class' => 'form-control'])}}
          </div>
        </div>
        <div class="modal-footer">
          {{Form::submit('Crear', ['class' => 'btn btn-danger'])}}
        </div>
      {!!Form::close()!!}
    </div>
  </div>
</div>

<!-- Modal - Actualizar -->
<div class="modal fade" id="ModalActualizar" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Actualizar Puesto de Votación</h4>
      </div>
      {!!Form::open(['action' => ['PuestosVotacionController@update', 1], 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::hidden('id', '', ['id' => 'idInput'])}}
            {{Form::label('', 'Comuna')}}
            {{Form::select('comuna', $comunas, null, ['id' => 'comunaInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar comuna...', 'required'])}}
            {{Form::label('', 'Barrio')}}
            {{Form::select('barrio', $barrios, null, ['id' => 'barrioInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar barrio...', 'required'])}}
            {{Form::label('', 'Nombre')}}
            {{Form::text('nombre', '', ['id' => 'nombreInput', 'class' => 'form-control', 'required'])}}
            {{Form::label('', 'Número de mesas')}}
            {{Form::number('mesas', '', ['id' => 'mesasInput', 'class' => 'form-control'])}}
            {{Form::hidden('ruta', url()->current()."?".http_build_query($_GET))}}
            {{Form::hidden('_method', 'PUT')}}
          </div>
        </div>
        <div class="modal-footer">
          {{Form::submit('Actualizar', ['class' => 'btn btn-danger'])}}
        </div>
      {!!Form::close()!!}
    </div>
  </div>
</div>

<!-- Modal - Eliminar -->
<div class="modal fade" id="ModalEliminar" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Eliminar Puesto de Votación</h4>
      </div>
      {!!Form::open(['action' => 'PuestosVotacionController@destroy', 'method' => 'POST'])!!}
        <div class="modal-body">
	      	<p>¿Seguro que desea eliminar?</p>
	      	{{Form::hidden('id', '', ['id' => 'idInput'])}}
          {{Form::hidden('ruta', url()->current()."?".http_build_query($_GET))}}
	      </div>
        {{Form::hidden('_method', 'DELETE')}}
        <div class="modal-footer">
          {{Form::submit('Sí', ['class' => 'btn btn-danger'])}}
        </div>
      {!!Form::close()!!}
    </div>
  </div>
</div>
@endsection