<div id="tablaSCompromisos">
  <div class="table-responsive">
    <table class="table table-striped table-bordered" style="margin: 0">
      <thead>
        <tr>
          <th>Compromiso</th>
          <th>Descripción</th>
          <th>Cumplimiento</th>
          <th>Costo</th>
        </tr>
      </thead>
      <tbody>
      @if(!(sizeof($compromisos)))
        <tr style="text-align: center">
          <td colspan=4>Este líder no tiene compromisos</td>
        </tr>
      @endif
      @foreach($compromisos as $compromiso)
        <tr style="text-align: left">
          <td>{{$compromiso->nombre}}</td>
          <td>{{$compromiso->descripcion}}</td>
          <td>{!!($compromiso->cumplimiento) ? '<i class="fa fa-check" aria-hidden="true" style="color:#31f931"></i>' : '<i class="fa fa-times" aria-hidden="true" style="color:red"></i>'!!}</td>
          <td>{{$compromiso->costo}}</td>                                            
        </tr>
      @endforeach
      </tbody>
    </table>
  </div>
</div>
<?php $nombreZona = "Compromisos"; $busq = '' ?>
@include('pags.mapa.pagAjax');