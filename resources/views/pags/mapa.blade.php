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
        <button id="irabajo" type="button" class="btn btn-default pull-right" style="margin-right:15px;">Ir abajo</button>
      </div>
      <!-- <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="false">
        <div class="carousel-inner" role="listbox">
          <div class="item active">
            <div class="row table-responsive" style="text-align: center; margin-left: 0px; margin-right: 0px">
              <object id='ant_svg' data='map_antioquia.svg' type='image/svg+xml'></object>
            </div>
          </div>
          <div class="item">
            <div class="row table-responsive" style="text-align: center; margin-left: 0px; margin-right: 0px">
              <object id='ant_svg' data='map_med_solo.svg' type='image/svg+xml' style="height: 530px;"></object>
            </div>
          </div>
        </div>
        <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev" style="color: black; background: transparent;">
	        <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
	        <span class="sr-only">Previous</span>
	      </a>
        <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next" style="color: black; background: transparent;">
	        <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
	        <span class="sr-only">Next</span>
	      </a>
      </div> -->
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
      </div>
      <div id="pagVLideres" class="fixed-table-pagination row" style="margin-left: 0px; margin-right: 0px"></div>

      <div class="fixed-table-pagination">

      </div>
      @endif
      <hr class="style-one">
            
      <div id="tablaVResumen" class="row" style="margin-left: 0px; margin-right: 0px"></div>

      <button id="irarriba" type="button" class="btn btn-default pull-right">Ir arriba</button>
    </div>
  </div>
</div>

<script type="text/javascript">
  var municipiosDatos = {!!$municipios!!};
  var munSelec = <?php echo ($idmun) ? $idmun : 0 ?>;
</script>

<script type="text/javascript" src="{{asset('js/mapa.js')}}"></script>

@endsection