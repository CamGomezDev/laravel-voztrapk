@extends('pags.administracion')
@section('seccion')
<script type="text/javascript" src="{{asset('js/lideres.js')}}"></script>
<div class="panel panel-default">
	<div class="panel-heading" id="head">
		<h4>Líderes</h4>
	</div>
	<div class="panel-body">
    <?php $import = true; $alt = true; $panelsup = ['Lideres','Lideres','lideres','Líder']; ?>
    @include('inc.panel-sup')
    <div class="table-responsive">
      <table class="table table-striped table-bordered" style="margin-bottom: 0px">
        <thead>
          <tr>
            <th>{{($sec == 'Med') ? 'Comuna' : 'Municipio'}}</th>
            <th>Nombre</th>
            <th>Cédula</th>
            <th>Correo</th>
            <th>Teléfono</th>
            <th>Nivel</th>
            <th>Tipo de líder</th>
            <th>Activo</th>
            @if($sec == 'Med')
            <th>Puesto de votación</th>
            <th>Barrio</th>
            @endif
            <th>Votos estimados</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        @if($totRows > 0)
          @foreach($lideres as $lider)
          <tr>
            <td>{{($sec == 'Med') ? $lider->id_comuna : $lider->municipio->nombre}}</td>
            <td>{{$lider->nombre}}</td>
            <td>{{$lider->cedula}}</td>
            <td>{{$lider->correo}}</td>
            <td>{{$lider->telefono}}</td>
            <td>{{$lider->nivel}}</td>
            <td>{{$lider->tipolider}}</td>
            <td>{!!($lider->activo) ? '<i class="fa fa-check" aria-hidden="true" style="color:#31f931"></i>' : '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>'!!}</td>
            @if($sec == 'Med')
            <td>{{$lider->puesto_votacion->nombre}}</td>
            <td>{{$lider->puesto_votacion->barrio->nombre}}</td>
            @endif
            <td>{{$lider->votosestimados}}</td>
            <td style="text-align: center">
              <h4 style="margin: 0;">
                <a type="button" data-toggle="modal" data-target="#ModalActualizar"
                  data-id=            "{{$lider->id}}"
                  data-nombre=        "{{$lider->nombre}}"
                  data-cedula=        "{{$lider->cedula}}"
                  data-correo=        "{{$lider->correo}}"
                  data-telefono=      "{{$lider->telefono}}"
                  data-nivel=         "{{$lider->nivel}}"
                  data-tipolider=     "{{$lider->tipolider}}"
                  data-activo=        "{{$lider->activo}}"
                  data-id_municipio=  "{{($sec == 'Med') ? $lider->comuna->id : $lider->municipio->id}}"
                  data-puesto=        "{{(isset($lider->puesto_votacion_id)) ? $lider->puesto_votacion_id : 0}}"
                  data-votosestimados="{{$lider->votosestimados}}"
                  ><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right: 10px"></i></a>
                <a type="button" data-toggle="modal" data-target="#ModalEliminar" data-id="{{$lider->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
              </h4>
            </td>
          </tr>
          @endforeach
        @else
          <tr>
            <td colspan="{{($sec == 'Med') ? '11' : 10}}">No se encontraron resultados</td>
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
    {{$lideres->appends($queryString)->links()}}
    @include('inc.filas')
  </div>
</div>

<!-- Modal - Crear -->
<div class="modal fade" id="ModalCrear" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear Líder</h4>
      </div>
      {!!Form::open(['action' => array('LideresController@store', $sec), 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::label('', ($sec == 'Med') ? 'Comuna' : 'Municipio')}}
            {{Form::select('id_municipio', $seclista, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar '.(($sec == 'Med') ? 'comuna' : 'municipio').'...', 'required'])}}
            {{Form::label('', 'Nombre')}}
            {{Form::text('nombre', '', ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Cédula')}}
            {{Form::text('cedula', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Correo')}}
            {{Form::text('correo', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Teléfono')}}
            {{Form::text('telefono', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Nivel')}}
            {{Form::text('nivel', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Tipo de líder')}}
            {{Form::text('tipolider', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Activo')}}
            {{Form::select('activo', ['1'=>'Activo', '0'=>'Inactivo'], null, ['class' => 'form-control', 'required'])}}
            @if($sec == 'Med')
            {{Form::label('', 'Puesto de votación')}}
            {{Form::select('puesto', $puestosvotacion, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar puesto...', 'required'])}}
            @endif
            {{Form::label('', 'Votos estimados')}}
            {{Form::number('votosestimados', '', ['class' => 'form-control', 'required'])}}
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
        <h4 class="modal-title" id="myModalLabel">Crear Líder</h4>
      </div>
      {!!Form::open(['action' => array('LideresController@update', $sec), 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::hidden('id', '', ['id' => 'idInput'])}}
            {{Form::label('', ($sec == 'Med') ? 'Comuna' : 'Municipio')}}
            {{Form::select('id_municipio', $seclista, null, ['id' => 'id_municipioInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar '.(($sec == 'Med') ? 'comuna' : 'municipio').'...', 'required'])}}
            {{Form::label('', 'Nombre')}}
            {{Form::text('nombre', '', ['id' => 'nombreInput', 'class' => 'form-control', 'required'])}}
            {{Form::label('', 'Cédula')}}
            {{Form::text('cedula', '', ['id' => 'cedulaInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Correo')}}
            {{Form::text('correo', '', ['id' => 'correoInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Teléfono')}}
            {{Form::text('telefono', '', ['id' => 'telefonoInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Nivel')}}
            {{Form::text('nivel', '', ['id' => 'nivelInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Tipo de líder')}}
            {{Form::text('tipolider', '', ['id' => 'tipoliderInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Activo')}}
            {{Form::select('activo', ['1'=>'Activo', '0'=>'Inactivo'], null, ['id' => 'activoInput', 'class' => 'form-control', 'required'])}}
            @if($sec == 'Med')
            {{Form::label('', 'Puesto de votación')}}
            {{Form::select('puesto', $puestosvotacion, null, ['id' => 'puestoInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar puesto...', 'required'])}}
            @endif
            {{Form::label('', 'Votos estimados')}}
            {{Form::text('votosestimados', '', ['id' => 'votosestimadosInput', 'class' => 'form-control', 'required'])}}
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
        <h4 class="modal-title">Eliminar líder</h4>
      </div>
      {!!Form::open(['action' => 'LideresController@destroy', 'method' => 'POST'])!!}
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