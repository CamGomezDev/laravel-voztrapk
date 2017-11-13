@extends('layouts.app')

@section('content')

<div class="container">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="row" style="margin-bottom: 15px">
        <button id="irabajo" type="button" class="btn btn-default pull-right" style="margin-right:15px;">Ir abajo</button>
      </div>

      <div class="row">
        <div class="col-md-8">
          <div class="table-responsive" style="text-align: center; margin-left: 0px; margin-right: 0px">
            <object id='ant_svg' data='{{asset('svg/map_antioquia.svg')}}' type='image/svg+xml'></object>
          </div>
        </div>
        <div class="col-md-4">
          <div style="margin-left: 0px ; margin-right: 0px">
            <div class="vcenter-parent" style="margin:0px 0px 10px 0px">
              <h4 class="vcenter-parent pull-left">
                &nbsp{{$subregion->nombre}}
              </h4>
            </div>
            <div class="table-responsive">
              <table class="table table-striped table-bordered" style="margin-bottom: 0px">
                <thead>
                  <tr>
                    <th>Líderes</th>
                    <th>Municipios</th>
                    <th>Población</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>{{sizeof($subregion->liders)}}</td>
                    <td>{{sizeof($subregion->municipios)}}</td>
                    <td>{{$subregion->poblacion}}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>

      <hr class="style-one" id="inicioTablas" style="margin: 15px 0px 15px 0px">
      <div id="tablaVFilasElectorales" class="row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="pagVFilasElectorales" class="fixed-table-pagination row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="ModalPoblacionWrapV"></div>

      <hr class="style-one" style="margin: 15px 0px 15px 0px">
      <div id="tablaVLideres" class="row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="pagVLideres" class="fixed-table-pagination row" style="margin-left: 0px; margin-right: 0px"></div>

      <button id="irarriba" type="button" class="btn btn-default pull-right">Ir arriba</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var subregionesDatos = {!!$subregiones!!};
  var subSelec = {{$idsub}};
</script>

<script type="text/javascript" src="{{asset('js/mapaSubs.js')}}"></script>

@endsection