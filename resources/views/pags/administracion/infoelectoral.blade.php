@extends('pags.administracion')
@section('seccion')
<script type="text/javascript" src="{{asset('js/infoelectoral.js')}}"></script>
<div class="panel panel-default">
	<div class="panel-heading" id="head">
		<h4>Información Electoral</h4>
	</div>
	<div class="panel-body">
    <?php $import = true; $panelsup = ['InfoElectoral','FilasElectorales','filasElectorales','Fila Electoral']; ?>
    @include('inc.panel-sup')
    <div class="table-responsive">
      <table class="table table-striped table-bordered" style="margin-bottom: 0px">
        <thead>
          <tr>
            <th>{{($sec == 'Med') ? 'Comuna' : 'Municipio'}}</th>
            <th>Corporación</th>
            <th>Votos totales</th>
            <th>Votos candidato</th>
            <th>Votos partido</th>
            <th>Potencial electoral</th>
            <th>Año</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
        @if($totRows > 0)
          @foreach($filasElectorales as $filaElectoral)
          <tr>
            <td>{{($sec == 'Med') ? $filaElectoral->comuna->nombre : $filaElectoral->municipio->nombre}}</td>
            <td>{{$filaElectoral->corporacion->nombre}}</td>
            <td>{{$filaElectoral->votostotales}}</td>
            <td>{{$filaElectoral->votoscandidato}}</td>
            <td>{{$filaElectoral->votospartido}}</td>
            <td>{{$filaElectoral->potencialelectoral}}</td>
            <td>{{$filaElectoral->anio}}</td>
            <td style="text-align: center">
              <h4 style="margin: 0;">
                <a type="button" data-toggle="modal" data-target="#ModalActualizar"
                  data-id=                "{{$filaElectoral->id}}"
                  data-id_municipio=      "{{($sec == 'Med') ? $filaElectoral->comuna->id : $filaElectoral->municipio->id}}"
                  data-id_corporacion=    "{{$filaElectoral->corporacion->id}}"
                  data-votostotales=      "{{$filaElectoral->votostotales}}"
                  data-votoscandidato=    "{{$filaElectoral->votoscandidato}}"
                  data-votospartido=      "{{$filaElectoral->votospartido}}"
                  data-potencialelectoral="{{$filaElectoral->potencialelectoral}}"
                  data-anio=              "{{$filaElectoral->anio}}"><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right: 10px"></i></a>
                <a type="button" data-toggle="modal" data-target="#ModalEliminar" data-id="{{$filaElectoral->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
              </h4>
            </td>
          </tr>
          @endforeach
        @else
          <tr>
            <td colspan=8>No se encontraron resultados</td>
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
    {{$filasElectorales->appends($queryString)->links()}}
    @include('inc.filas')
  </div>
</div>

<!-- Modal - Crear -->
<div class="modal fade" id="ModalCrear" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Crear Fila Electoral</h4>
      </div>
      {!!Form::open(['action' => array('FilasElectoralesController@store', $sec), 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::label('', ($sec == 'Med') ? 'Comuna' : 'Municipio')}}
            {{Form::select('id_municipio', $seclista, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar '.(($sec == 'Med') ? 'comuna' : 'municipio').'...'])}}
            {{Form::label('', 'Corporación')}}
            {{Form::select('id_corporacion', $corporaciones, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar corporación...'])}}
            {{Form::label('', 'Votos totales')}}
            {{Form::number('votostotales', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Votos candidato')}}
            {{Form::number('votoscandidato', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Votos partido')}}
            {{Form::number('votospartido', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Potencial electoral')}}
            {{Form::number('potencialelectoral', '', ['class' => 'form-control'])}}
            {{Form::label('', 'Año')}}
            {{Form::number('anio', '', ['class' => 'form-control'])}}
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
        <h4 class="modal-title" id="myModalLabel">Actualizar Fila Electoral</h4>
      </div>
      {!!Form::open(['action' => array('FilasElectoralesController@update', $sec), 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::hidden('id', '', ['id' => 'idInput'])}}
            {{Form::label('', ($sec == 'Med') ? 'Comuna' : 'Municipio')}}
            {{Form::select('id_municipio', $seclista, null, ['id' => 'id_municipioInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar '.($sec == 'Med') ? 'comuna' : 'municipio'.'...'])}}
            {{Form::label('', 'Corporación')}}
            {{Form::select('id_corporacion', $corporaciones, null, ['id' => 'id_corporacionInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar corporación...'])}}
            {{Form::label('', 'Votos totales')}}
            {{Form::number('votostotales', '', ['id' => 'votostotalesInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Votos candidato')}}
            {{Form::number('votoscandidato', '', ['id' => 'votoscandidatoInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Votos partido')}}
            {{Form::number('votospartido', '', ['id' => 'votospartidoInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Potencial electoral')}}
            {{Form::number('potencialelectoral', '', ['id' => 'potencialelectoralInput', 'class' => 'form-control'])}}
            {{Form::label('', 'Año')}}
            {{Form::number('anio', '', ['id' => 'anioInput', 'class' => 'form-control'])}}
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
        <h4 class="modal-title">Eliminar Fila Electoral</h4>
      </div>
      {!!Form::open(['action' => 'FilasElectoralesController@destroy', 'method' => 'POST'])!!}
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