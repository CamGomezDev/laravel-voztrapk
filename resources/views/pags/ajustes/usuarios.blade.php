@extends('pags.ajustes')
@section('seccion')
<script type="text/javascript" src="{{asset('js/usuarios.js')}}"></script>
<div class="panel panel-default">
	<div class="panel-heading" id="head">
		<h4>Usuarios</h4>
	</div>
	<div class="panel-body">
    <?php $import = false; $alt = false; $sec = ''; $panelsup = ['Usuarios','Users','usuarios','Usuario']; ?>
    @include('inc.panel-sup')
    <div class="table-responsive">
      <table class="table table-striped table-bordered" style="margin-bottom: 0px">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Correo</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        @if($totRows > 0)
          @foreach($usuarios as $usuario)
          <tr>
            <td>{{$usuario->name}}</td>
            <td>{{$usuario->email}}</td>
            <td>{{$usuario->rol->nombre}}</td>
            <td style="text-align: center">
              <h4 style="margin: 0;">
                <a type="button" data-toggle="modal" data-target="#ModalActualizar"
                  data-id=    "{{$usuario->id}}"
                  data-name=  "{{$usuario->name}}"
                  data-email= "{{$usuario->email}}"
                  data-id_rol="{{$usuario->id_rol}}"><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right: 10px"></i></a>
                <a type="button" data-toggle="modal" data-target="#ModalEliminar" data-id="{{$usuario->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
              </h4>
            </td>
          </tr>
          @endforeach
        @else
          <tr>
            <td colspan=4>No se encontraron resultados</td>
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
    {{$usuarios->appends($queryString)->links()}}
    @include('inc.filas')
  </div>
</div>

<!-- Modal - Crear -->
<div class="modal fade" id="ModalCrear" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear Usuario</h4>
      </div>
      {!!Form::open(['action' => 'Auth\RegisterController@register', 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::label('', 'Nombre')}}
            {{Form::text('name', '', ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Correo electrónico')}}
            {{Form::text('email', '', ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Contraseña')}}
            {{Form::password('password', ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Confirmar contraseña')}}
            {{Form::password('password_confirmation', ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Rol')}}
            {{Form::select('id_rol', $roles, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar rol...', 'required'])}}
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
        <h4 class="modal-title" id="myModalLabel">Actualizar Usuario</h4>
      </div>
      {!!Form::open(['action' => ['UsersController@update', 1], 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::hidden('id', '1', ['id' => 'idInput'])}}
            {{Form::label('', 'Nombre')}}
            {{Form::text('name', '', ['id' => 'nameInput', 'class' => 'form-control', 'required'])}}
            {{Form::label('', 'Correo electrónico')}}
            {{Form::text('email', '', ['id' => 'emailInput', 'class' => 'form-control', 'required'])}}
            {{Form::label('', 'Contraseña (dejar vacío para no modificarla)')}}
            {{Form::password('password', ['class' => 'form-control'])}}
            {{Form::label('', 'Confirmar contraseña')}}
            {{Form::password('password_confirmation', ['class' => 'form-control'])}}
            {{Form::label('', 'Rol')}}
            {{Form::select('id_rol', $roles, null, ['id' => 'id_rolInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar rol...', 'required'])}}
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
        <h4 class="modal-title">Eliminar Usuario</h4>
      </div>
      {!!Form::open(['action' => 'UsersController@destroy', 'method' => 'POST'])!!}
        <div class="modal-body">
	      	<p>¿Seguro que desea eliminar este usuario, removiendo todo su acceso?</p>
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