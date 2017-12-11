@extends('pags.administracion')
@section('seccion')
<script type="text/javascript" src="{{asset('js/compromisos.js')}}"></script>
<div class="panel panel-default">
	<div class="panel-heading" id="head">
		<h4>Compromisos</h4>
	</div>
	<div class="panel-body">
    <?php $import = false; $alt = true; $panelsup = ['Compromisos','Compromisos','compromisos','Compromiso']; ?>
    @include('inc.panel-sup')
    <div class="table-responsive">
      <table class="table table-striped table-bordered" style="margin-bottom: 0px">
        <thead>
          <tr>
            <th>Líder</th>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cumplimiento</th>
            <th>Costo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        @if($totRows > 0)
          @foreach($compromisos as $compromiso)
          <tr>
            <td>{{$compromiso->lider->nombre}}</td>
            <td>{{$compromiso->nombre}}</td>
            <td>{{$compromiso->descripcion}}</td>
            <td>{!!($compromiso->cumplimiento) ? '<i class="fa fa-check" aria-hidden="true" style="color:#31f931"></i>' : '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>'!!}</td>
            <td>{{$compromiso->costo}}</td>
            <td style="text-align: center">
              <h4 style="margin: 0;">
                <a type="button" data-toggle="modal" data-target="#ModalActualizar"
                  data-id=          "{{$compromiso->id}}"
                  data-id_lider=    "{{$compromiso->lider->id}}"
                  data-nombre=      "{{$compromiso->nombre}}"
                  data-descripcion= "{{$compromiso->descripcion}}"
                  data-cumplimiento="{{$compromiso->cumplimiento}}"
                  data-costo=       "{{$compromiso->costo}}"><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right: 10px"></i></a>
                <a type="button" data-toggle="modal" data-target="#ModalEliminar" data-id="{{$compromiso->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
              </h4>
            </td>
          </tr>
          @endforeach
        @else
          <tr>
            <td colspan=6>No se encontraron resultados</td>
          </tr>
        @endif
        </tbody>
      </table>
    </div>
    <?php
    $queryString = [];
    if (isset($_GET['q'])) {
      $queryString['q'] = $_GET['q'];
    } if (isset($_GET['rows'])) {
      $queryString['rows'] = $_GET['rows'];
    } if (isset($_GET['page'])) {
      $queryString['page'] = $_GET['page'];
    }
    ?>
    {{$compromisos->appends($queryString)->links()}}
    @include('inc.filas')
  </div>
</div>

<!-- Modal - Crear -->
<div class="modal fade" id="ModalCrear" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear Compromiso</h4>
      </div>
      {!!Form::open(['action' => array('CompromisosController@store', $sec), 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::label('', 'Líder')}}
            {{Form::select('id_lider', $lideres, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar líder en '.$secNom.'...', 'required'])}}
            {{Form::label('', 'Nombre')}}
            {{Form::text('nombre', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Descripción')}}
            {{Form::textarea('descripcion', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Cumplimiento')}}
            {{Form::select('cumplimiento', ['1'=>'Cumplido', '0'=>'Pendiente'], null, ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Costo')}}
            {{Form::number('costo', '', ['class' => 'form-control'])}}
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
        <h4 class="modal-title" id="myModalLabel">Actualizar Compromiso</h4>
      </div>
      {!!Form::open(['action' => ['CompromisosController@update', 1], 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::hidden('id', '', ['id' => 'idInput'])}}
            {{Form::label('', 'Líder')}}
            {{Form::select('id_lider', $lideres, null, ['id' => 'id_liderInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar líder en '.$secNom.'...', 'required'])}}
            {{Form::label('', 'Nombre')}}
            {{Form::text('nombre', '', ['id' => 'nombreInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Descripción')}}
            {{Form::textarea('descripcion', '', ['id' => 'descripcionInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Cumplimiento')}}
            {{Form::select('cumplimiento', ['1'=>'Cumplido', '0'=>'Pendiente'], null, ['id' => 'cumplimientoInput', 'class' => 'form-control', 'required'])}}
            {{Form::label('', 'Costo')}}
            {{Form::number('costo', '', ['id' => 'costoInput', 'class' => 'form-control'])}}
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
        <h4 class="modal-title">Eliminar compromiso</h4>
      </div>
      {!!Form::open(['action' => 'CompromisosController@destroy', 'method' => 'POST'])!!}
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