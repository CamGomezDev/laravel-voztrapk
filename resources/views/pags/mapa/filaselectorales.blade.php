<div id="tablaSFilasElectorales">
  <div class="row vcenter-parent" style="margin: 0px 5px 10px 5px">
    <h4 class="vcenter-parent pull-left">
      <form class="pull-left" action="../Backend/Exportar.php" method="GET">
        <input type="hidden" name="municnombre" value="">
        <input type="hidden" name="municid" value="">
        <button type="submit" class="btn btn-default" style="padding-right:7px; padding-left:7px" name="exportarInfosElectoralesMapa"><i class="fa fa-download fa-lg" aria-hidden="true"></i></button>
      </form>
      &nbspInformación Electoral - {{$municipio->nombre}} - Subregión: {{$subregion}} - Población: {{($municipio->poblacion) ? $municipio->poblacion : 0}}
    </h4>
    <div class="vcenter-parent pull-right" style="margin-left:auto;">
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#ModalPoblacion"
        data-id="{{$municipio->id}}" data-poblacion="{{$municipio->poblacion}}">Editar</button>
    </div>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered" style="margin-bottom: 0px">
      <thead>
        <tr>
          <th>Corporación</th>
          <th>Votos totales</th>
          <th>Votos candidato</th>
          <th>Votos partido</th>
          <th>Potencial electoral</th>
          <th>Año</th>
        </tr>
      </thead>
      <tbody>
      @if(!(sizeof($filasElectorales)))
        <tr>
          <td colspan=6>Este municipio no tiene información electoral</td>
        </tr>
      @endif
      @foreach($filasElectorales as $filaElectoral)
        <tr>
          <td>{{$filaElectoral->corporacion->nombre}}</td>
          <td>{{$filaElectoral->votostotales}}</td>
          <td>{{$filaElectoral->votoscandidato}}</td>
          <td>{{$filaElectoral->votospartido}}</td>
          <td>{{$filaElectoral->potencialelectoral}}</td>
          <td>{{$filaElectoral->anio}}</td>
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<?php $nombreZona = 'FilasElectorales'; $busq = ''; ?>
@include('pags.mapa.pagAjax')


<!-- Modal - Población -->
<div id="ModalPoblacionWrapS">
  <div class="modal fade" id="ModalPoblacion" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Población de {{$municipio->nombre}}</h4>
        </div>
        {!!Form::open(['action' => 'MapaController@poblacion', 'method' => 'POST'])!!}
          <div class="modal-body">
            {{Form::hidden('id', '', ['id' => 'idInput'])}}
            {{Form::number('poblacion', '', ['id' => 'poblacionInput', 'class' => 'form-control'])}}
            {{Form::hidden('ruta', "Mapa?m=".$municipio->id)}}
          </div>
          <div class="modal-footer">
            {{Form::submit('Cambiar', ['class' => 'btn btn-danger'])}}
          </div>
        {!!Form::close()!!}
      </div>
    </div>
  </div>
</div>
