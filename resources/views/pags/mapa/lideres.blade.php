<div id="tablaSLideres">
  <div class="row vcenter-parent" style="margin: 0px 5px 0px 5px">
    <h4 class="vcenter-parent pull-left">
    <?php $sec = Request::segment(2) ?>
      <form class="pull-left" action="../ExportarLideresMapa{{$sec}}/{{$idcosa}}" method="GET">
        <button type="submit" class="btn btn-custom" style="padding-right:7px;padding-left:7px;margin-right:5px"><i class="fa fa-download fa-lg" aria-hidden="true"></i></button>
      </form>
      Líderes en {{$cosa}}
    </h4>
    <div class="vcenter-parent pull-right" style="margin-left:auto">
      <input type="text" class="form-control" id="busquedaLideres" placeholder="Buscar"/>
    </div>
  </div>
  <div id="tablaSLideresTabla" class="table-responsive">
    <table class="table table-striped table-bordered" style="margin-bottom: 0px; margin-top: 10px">
      <thead>
        <tr>
          <th></th>
          @if(isset($issetsub))
          <th>Municipio</th>
          @endif
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
        </tr>
      </thead>
      <tbody>
      @if(!(sizeof($lideres)))
        <?php
        if(isset($issetsub)) {
          $col = 10;
        } elseif($sec=='Med') {
          $col = 11;
        } else {
          $col = 9;
        }
        ?>
        <tr>
          <td colspan={{$col}}>Este lugar no tiene líderes</td>
        </tr>
      @endif
      @foreach($lideres as $lider)
        <tr>
          <td style="padding:0">
            <h4>
              <a onclick="obtCompromisos(this)" id="{{$lider->id}}">
                <i class="fa fa-plus-circle" aria-hidden="true"></i>
              </a>
            </h4>
          </td>
          @if(isset($issetsub))
          <td>{{$lider->municipio->nombre}}</td>
          @endif
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
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<?php $nombreZona = 'Lideres'; $busq = ''; ?>
@include('pags.mapa.pagAjax')