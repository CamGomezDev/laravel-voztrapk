@extends('layouts.app')

@section('content')

<div class="container">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="row" style="margin-bottom: 15px">
        <div class="pull-left" style="margin-left:15px">
          <input type="text" class="form-control " id="busquedaMapa" placeholder="Buscar comuna" />
          <div id="divBusqRes" class="dropdown">
            <ul id="cuadroResul" class="dropdown-menu">
            </ul>
          </div>
        </div>
        <button id="irabajo" type="button" class="btn btn-custom pull-right" style="margin-right:15px;">Ir abajo</button>
      </div>
      <div class="row">
        @if($idcom)
        <div class="col-md-8">
        @endif
          <div class="table-responsive" style="text-align: center; margin-left: 0px; margin-right: 0px">
            <object id='med_svg' data='{{asset('svg/map_med_solo.svg')}}' type='image/svg+xml'></object>
          </div>
        @if($idcom)
        </div>
        <hr class="style-one" id="inicioTablas" style="margin: 15px 0px 15px 0px">
        <div class="col-md-4">
          <div style="margin-left: 0px ; margin-right: 0px">
            <div class="vcenter-parent" style="margin:0px 0px 10px 0px">
              <h4 class="vcenter-parent pull-left">
                &nbsp{{$comuna->nombre}}
              </h4>
              <div class="vcenter-parent pull-right" style="margin-left:auto;">
                <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#ModalComuna"
                  data-id     ="{{$comuna->id}}" 
                  data-puestos="{{$comuna->puestos}}"
                  data-barrios="{{$comuna->barrios}}"
                  data-mesas  ="{{$comuna->mesas}}">Editar</button>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered" style="margin-bottom: 0px">
                <thead>
                  <tr>
                    <th>Puestos</th>
                    <th>Barrios</th>
                    <th>Mesas</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{$comuna->puestos}}</td>
                    <td>{{$comuna->barrios}}</td>
                    <td>{{$comuna->mesas}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        @endif
      </div>

      @if($idcom)
      <hr class="style-one" style="margin: 15px 0px 15px 0px">
      <div id="tablaVFilasElectorales" class="row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="pagVFilasElectorales" class="fixed-table-pagination row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="ModalPoblacionWrapV"></div>

      <hr class="style-one" style="margin: 15px 0px 15px 0px">
      <div id="tablaVLideres" class="row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="pagVLideres" class="fixed-table-pagination row" style="margin-left: 0px; margin-right: 0px"></div>
      @endif

      <hr class="style-one">           
      <div id="tablaVResumen" class="row" style="margin-left: 0px; margin-right: 0px"></div>

      <button id="irarriba" type="button" class="btn btn-custom pull-right">Ir arriba</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var comunasDatos = {!!$comunas!!};
  var comSelec = <?php echo ($idcom) ? $idcom : 0 ?>;
</script>

<script type="text/javascript" src="{{asset('js/mapaMed.js')}}"></script>

@if($idcom)
<!-- Modal - Editar comuna -->
<div class="modal fade" id="ModalComuna" role="dialog">
  <div class="modal-dialog modal-sm" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Editar comuna</h4>
      </div>
      {!!Form::open(['action' => 'MapaMedController@comuna', 'method' => 'POST'])!!}
        <div class="modal-body">
          {{Form::hidden('id', '', ['id' => 'idInput'])}}
          {{Form::label('', 'Puestos')}}
          {{Form::number('puestos', '', ['id' => 'puestosInput', 'class' => 'form-control'])}}
          {{Form::label('', 'Barrios')}}
          {{Form::number('barrios', '', ['id' => 'barriosInput', 'class' => 'form-control'])}}
          {{Form::label('', 'Mesas')}}
          {{Form::number('mesas', '', ['id' => 'mesasInput', 'class' => 'form-control'])}}
          {{Form::hidden('ruta', "Mapa/Med?c=".$comuna->id)}}
        </div>
        <div class="modal-footer">
          {{Form::submit('Cambiar', ['class' => 'btn btn-danger'])}}
        </div>
      {!!Form::close()!!}
    </div>
  </div>
</div>
@endif

@endsection
