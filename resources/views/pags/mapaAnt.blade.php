@extends('layouts.app')

@section('content')

<div class="container">
  <div class="panel panel-default">
    <div class="panel-body">
      <div class="row" style="margin-bottom: 15px">
        <div class="pull-left" style="margin-left:15px">
          <input type="text" class="form-control " id="busquedaMapa" placeholder="Buscar municipio" />
          <div id="divBusqRes" class="dropdown">
            <ul id="cuadroResul" class="dropdown-menu">
            </ul>
          </div>
        </div>
        <button id="irabajo" type="button" class="btn btn-custom pull-right" style="margin-right:15px;">Ir abajo</button>
      </div>

      
      <div class="row table-responsive" style="text-align: center; margin-left: 0px; margin-right: 0px">
        <object id='ant_svg' data='{{asset('svg/map_antioquia.svg')}}' type='image/svg+xml'></object>
      </div>

      @if($idmun)
      <hr class="style-one" id="inicioTablas" style="margin: 15px 0px 15px 0px">
      <div id="tablaVFilasElectorales" class="row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="pagVFilasElectorales" class="fixed-table-pagination row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="ModalPoblacionWrapV"></div>

      <hr class="style-one" style="margin: 15px 0px 15px 0px">
      <div id="tablaVLideres" class="row" style="margin-left: 0px; margin-right: 0px"></div>
      <div id="pagVLideres" class="fixed-table-pagination row" style="margin-left: 0px; margin-right: 0px"></div>
      
      <hr class="style-one" style="margin: 15px 0px 15px 0px">
      <div class="row" style="margin-left: 0px; margin-right: 0px">
        <div class="well well-md" data-toggle="collapse" style="margin-bottom:0px" href="#collapse{{$municipio->id}}">
          <h4 style="margin: 0px 15px 0px 15px">
            {{$municipio->nombre}} - Visitas: {{(count($municipio->visitas)) ? count($municipio->visitas) : 0}}
            <a class="pull-right" href="#"><i class="pull-right fa fa-sort-desc" aria-hidden="true"></i></a>
          </h4>
          <div class="table-responsive collapse" id="collapse{{$municipio->id}}" style="margin-top:15px">
            <table class="table table-striped table-bordered" style="margin-bottom: 0px">
              <thead>
                <tr>
                  <th>Notas</th>
                  <th>LLegada</th>
                  <th>Salida</th>
                </tr>
              </thead>
              <tbody>
              @if(count($municipio->visitas) > 0)
                @foreach($municipio->visitas as $visita)
                <tr>
                  <td>{{$visita->notas}}</td>
                  <td>{{$visita->llegada}}</td>
                  <td>{{$visita->salida}}</td>
                </tr>
                @endforeach
              @else
                <tr>
                  <td colspan=3>No se encontraron resultados</td>
                </tr>
              @endif
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div id="pagVLideres" class="fixed-table-pagination row" style="margin-left: 0px; margin-right: 0px"></div>

      <div class="fixed-table-pagination">

      </div>
      @endif
      <hr class="style-one">
            
      <div id="tablaVResumen" class="row" style="margin-left: 0px; margin-right: 0px"></div>

      <button id="irarriba" type="button" class="btn btn-custom pull-right">Ir arriba</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var municipiosDatos = {!!$municipios!!};
  var munSelec = <?php echo ($idmun) ? $idmun : 0 ?>;
</script>

<script type="text/javascript" src="{{asset('js/mapaAnt.js')}}"></script>

@endsection