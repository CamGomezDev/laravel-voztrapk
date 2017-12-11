<div id="tablaSFilasElectorales">
  <div class="row vcenter-parent" style="margin: 0px 5px 0px 5px">
    <h4 class="vcenter-parent pull-left">
      <form class="pull-left" action="../ExportarFilasElectoralesMapa{{Request::segment(2)}}/{{$idcosa}}" method="GET">
        <input type="hidden" name="municnombre" value="">
        <input type="hidden" name="municid" value="">
        <button type="submit" class="btn btn-custom" style="padding-right:7px;padding-left:7px;margin-right:5px" name="exportarInfosElectoralesMapa"><i class="fa fa-download fa-lg" aria-hidden="true"></i></button>
      </form>
      Información Electoral - {{$cosafrase}}
    </h4>
    @if($editar)
    <div class="vcenter-parent pull-right" style="margin-left:auto;">
      <button type="button" class="btn btn-custom" data-toggle="modal" data-target="#ModalPoblacion"
        data-id="{{$cosa->id}}" data-poblacion="{{$cosa->poblacion}}">Editar</button>
    </div>
    @endif
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered" style="margin-bottom: 0px; margin-top: 10px">
      <thead>
        <tr>
          <th>Corporación</th>
          <th>Potencial electoral</th>
          <th>Votos totales</th>
          <th>Votos partido</th>
          <th>Votos candidato</th>
          <th>Año</th>
        </tr>
      </thead>
      <tbody>
      @if(!(sizeof($filasElectorales)))
        <tr>
          <td colspan=6>Este lugar no tiene información electoral</td>
        </tr>
      @endif
      @foreach($filasElectorales as $filaElectoral)
        <tr>
          <td>{{$filaElectoral->corporacion->nombre}}</td>
          <td>{{$filaElectoral->potencialelectoral}}</td>
          <td>{{$filaElectoral->votostotales}}</td>
          <td>{{$filaElectoral->votospartido}}</td>
          <td>{{$filaElectoral->votoscandidato}}</td>
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
@if($editar)
<div id="ModalPoblacionWrapS">
  <div class="modal fade" id="ModalPoblacion" role="dialog">
    <div class="modal-dialog modal-sm" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">Población de {{$cosa->nombre}}</h4>
        </div>
        {!!Form::open(['action' => 'MapaAntController@poblacion', 'method' => 'POST'])!!}
          <div class="modal-body">
            {{Form::hidden('id', '', ['id' => 'idInput'])}}
            {{Form::number('poblacion', '', ['id' => 'poblacionInput', 'class' => 'form-control'])}}
            {{Form::hidden('ruta', "Mapa/Ant?m=".$cosa->id)}}
          </div>
          <div class="modal-footer">
            {{Form::submit('Cambiar', ['class' => 'btn btn-danger'])}}
          </div>
        {!!Form::close()!!}
      </div>
    </div>
  </div>
</div>
@endif