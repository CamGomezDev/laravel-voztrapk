<div id="tablaSLideres">
  <div class="row vcenter-parent" style="margin: 0px 5px 10px 5px">
    <h4 class="vcenter-parent pull-left">
      <form class="pull-left" action="../Backend/Exportar.php" method="GET">
        <input type="hidden" name="municnombre" value="">
        <input type="hidden" name="municid" value="">
        <button type="submit" class="btn btn-default" style="padding-right:7px; padding-left:7px" name="exportarInfosElectoralesMapa"><i class="fa fa-download fa-lg" aria-hidden="true"></i></button>
      </form>
      &nbspLíderes en {{$cosa}}
    </h4>
    <div class="vcenter-parent pull-right" style="margin-left:auto">
      <input type="text" class="form-control" id="busquedaLideres" placeholder="Buscar"/>
    </div>
  </div>
  <div id="tablaSLideresTabla" class="table-responsive">
    <table class="table table-striped table-bordered" style="margin-bottom: 0px">
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
          <th>Votos estimados</th>
        </tr>
      </thead>
      <tbody>
      @if(!(sizeof($lideres)))
        <tr>
          <td colspan={{(isset($issetsub)) ? '10' : '9'}}>Este lugar no tiene líderes</td>
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
          <td>{{($lider->activo == 1) ? 'Activo' : 'Inactivo'}}</td>
          <td>{{$lider->votosestimados}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<?php $nombreZona = 'Lideres'; $busq = ''; ?>
@include('pags.mapa.pagAjax')