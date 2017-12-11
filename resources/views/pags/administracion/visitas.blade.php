@extends('pags.administracion')
@section('seccion')
<script type="text/javascript" src="{{asset('js/visitas.js')}}"></script>
<div class="panel panel-default">
	<div class="panel-heading" id="head">
		<h4>Visitas</h4>
	</div>
	<div class="panel-body">
    <?php $import = false; $alt = false; $panelsup = ['Visitas','Visitas','visitas','Visita']; ?>
    @include('inc.panel-sup')
    @if($totRows > 0)
      @foreach($municipios as $municipio)
      <div class="well well-sm" data-toggle="collapse" href="#collapse{{$municipio->id}}" style="border: 1px solid rgb(190, 190, 190)">
        <b>{{$municipio->nombre}} - Visitas: {{(count($municipio->visitas)) ? count($municipio->visitas) : 0}}</b>
        <div class="table-responsive collapse" id="collapse{{$municipio->id}}" style="margin-top:15px">
          <table class="table table-striped table-bordered" style="margin-bottom: 0px">
            <thead>
              <tr>
                <th>Notas</th>
                <th>LLegada</th>
                <th>Salida</th>
                <th>Acciones</th>
              </tr>
            </thead>
            <tbody>
            @if(count($municipio->visitas) > 0)
              @foreach($municipio->visitas as $visita)
              <tr>
                <td>{{$visita->notas}}</td>
                <td>{{$visita->llegada}}</td>
                <td>{{$visita->salida}}</td>
                <td style="text-align: center">
                  <h4 style="margin: 0;">
                    <a type="button" data-toggle="modal" data-target="#ModalActualizar"
                      data-id=          "{{$visita->id}}"
                      data-notas=       "{{$visita->notas}}"
                      data-llegada=     "{{$visita->llegada}}"
                      data-salida=      "{{$visita->salida}}"
                      data-id_municipio="{{$visita->id_municipio}}"><i class="fa fa-pencil-square-o" aria-hidden="true" style="margin-right: 10px"></i></a>
                    <a type="button" data-toggle="modal" data-target="#ModalEliminar" data-id="{{$visita->id}}"><i class="fa fa-trash-o" aria-hidden="true"></i></a>
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
      </div>
        
      @endforeach
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
      {{$municipios->appends($queryString)->links()}}
      @include('inc.filas')
    @else
    <div class="well well-sm"><b>No se encontraron resultados</b></div>
    @endif
  </div>
</div>

<!-- Modal - Crear -->
<div class="modal fade" id="ModalCrear" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Nueva visita</h4>
      </div>
      {!!Form::open(['action' => array('VisitasController@store', $sec), 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::label('', 'Notas')}}
            {{Form::text('notas', '', ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Fecha de entrada')}}
            {{Form::date('llegada', \Carbon\Carbon::now(), ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Fecha de salida')}}
            {{Form::date('salida', \Carbon\Carbon::now(), ['class' => 'form-control', 'required'])}}
            {{Form::label('', 'Municipio')}}
            {{Form::select('id_municipio', $municipiosInput, null, ['class' => 'form-control', 'placeholder' => 'Seleccionar municipio...', 'required'])}}
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
        <h4 class="modal-title" id="myModalLabel">Actualizar Visita</h4>
      </div>
      {!!Form::open(['action' => ['VisitasController@update', 1], 'method' => 'POST'])!!}
        <div class="modal-body">
          <div class="form-group">
            {{Form::hidden('id', '', ['id' => 'idInput'])}}
            {{Form::label('', 'Notas')}}
            {{Form::text('notas', '', ['id' => 'notasInput', 'class' => 'form-control', 'required'])}}
            {{Form::label('', 'Fecha de entrada')}}
            {{Form::date('llegada', \Carbon\Carbon::now(), ['id' => 'llegadaInput', 'class' => 'form-control', 'required'])}}
            {{Form::label('', 'Fecha de salida')}}
            {{Form::date('salida', \Carbon\Carbon::now(), ['id' => 'salidaInput', 'class' => 'form-control', 'required'])}}
            {{Form::label('', 'Municipio')}}
            {{Form::select('id_municipio', $municipiosInput, null, ['id' => 'id_municipioInput', 'class' => 'form-control', 'placeholder' => 'Seleccionar municipio...', 'required'])}}
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
        <h4 class="modal-title">Eliminar Visita</h4>
      </div>
      {!!Form::open(['action' => 'VisitasController@destroy', 'method' => 'POST'])!!}
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