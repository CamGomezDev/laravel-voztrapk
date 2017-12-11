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
            <object id='med_svg' data='{{asset('svg/map_med_tod.svg')}}' type='image/svg+xml'></object>
          </div>
        @if($idcom)
        </div>
        <hr class="style-one" id="inicioTablas" style="margin: 15px 0px 15px 0px">
        <div class="col-md-4">
          <div style="margin-left: 0px ; margin-right: 0px">
            <div class="vcenter-parent" style="margin:0px">
              <h4 class="vcenter-parent pull-left">
                <form class="pull-left" action="../ExportarPuestosMapaMed/{{$idcom}}" method="GET">
                  <button type="submit" class="btn btn-custom" style="padding-right:7px; padding-left:7px; margin-right:5px"><i class="fa fa-download fa-lg" aria-hidden="true"></i></button>
                </form>
                Puestos - {{$comuna->nombre}}
              </h4>
            </div>
            <?php
            $mesas = 0;
            foreach($comuna->puesto_votacions as $puesto) {
              $mesas = $mesas + $puesto->mesas;
            }
            $barrios = count($comuna->barrios);
            ?>
            <div class="table-responsive">
              <table class="table table-striped table-bordered" style="margin-bottom: 0px">
                <thead>
                  <tr>
                    <th>Puesto</th>
                    <th>Mesas</th>
                    <th>Barrio</th>
                  </tr>
                </thead>
                <tbody>
                @if(count($comuna->puesto_votacions))
                  @foreach($comuna->puesto_votacions as $puesto)
                  <tr>
                    <td>{{$puesto->nombre}}</td>
                    <td>{{$puesto->mesas}}</td>
                    <td>{{$puesto->barrio->nombre}}</td>
                  </tr>
                  @endforeach
                @else
                  <tr>
                    <td colspan=3>No se han añadido puestos de votación</td>
                  </tr>
                @endif
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
  var comSelec = <?php echo($idcom) ? $idcom : 0 ?>;
</script>

<script type="text/javascript" src="{{asset('js/mapaMed.js')}}"></script>

@endsection
