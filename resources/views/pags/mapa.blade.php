@extends('layouts.app')

@section('content')

<script type="text/javascript">
  var municipiosDatos = {!!$municipios!!};
  var munSelec = <?php echo ($idmun) ? $idmun : 0 ?>;
</script>


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

      <div class="fixed-table-pagination">

      </div>
      @endif
      <hr class="style-one">
            
      <div id="tablaVResumen" class="row" style="margin-left: 0px; margin-right: 0px"></div>

      <button id="irarriba" type="button" class="btn btn-default pull-right">Ir arriba</button>
    </div>
  </div>
</div>

<script type="text/javascript" src="{{asset('js/mapa.js')}}"></script>

@endsection