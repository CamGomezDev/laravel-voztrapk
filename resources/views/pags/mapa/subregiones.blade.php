<div id="tablaSResumen">
  <div class="row" style="margin:0px; margin-bottom:10px">
    <h4 class="vcenter-parent pull-left" style="margin-left: 5px; text-align: left">
      <form action="../ExportarResumenSub" method="GET">
        <input type="hidden" name="id_corporacion" value="{{$idcorp}}">
        <input type="hidden" name="anio" value="{{$anio}}">
        <button type="submit" class="btn btn-default" style="padding-right:7px; padding-left:7px" name="exportarResumenMapa"><i class="fa fa-download fa-lg" aria-hidden="true"></i></button>
      </form>
      <span>&nbspResumen por subregión - Votación para</span>
    </h4>
    <h4 class="vcenter-parent pull-left" style="margin-left: 5px; text-align: left">
      <span style="display: inline-block;">
        <span class="btn-group dropdown pull-left" style="position: relative; display: inline-block; vertical-align: middle;">
          <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <h4 style="margin: 0px"><?php echo $corp ?> <span class="caret"></span></h4>
          </button>
          <ul class="dropdown-menu" style="margin: 0px; padding: 0px; border-radius: 2px">
          @foreach($corporaciones as $corporacion)
            @if($corporacion->id !== $idcorp)
            <li>
              <a href=# style="margin: 3px; padding: 3px;" onclick="conseguirResumen({{$corporacion->id}},{{$anio}}); return false">
                <h4 style="margin: 0px; padding: 0px">
                  {{$corporacion->nombre}}
                </h4>
              </a>
            </li>
            @endif
          @endforeach
          </ul>
        </span>
      </span>
      &nbspdel año&nbsp
      <span style="display: inline-block;">
        <span class="btn-group dropdown pull-left" style="position: relative; display: inline-block; vertical-align: middle;">
          <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
            <h4 style="margin: 0px">{{$anio}} <span class="caret"></span></h4>
          </button>
          <ul class="dropdown-menu" style="margin: 0px; padding: 0px; border-radius: 2px">
          @foreach($anios as $anioi)
            @if($anioi->anio !== $anio)
            <li><a href=# style="margin: 3px; padding: 3px;" onclick="conseguirResumen({{$idcorp}},{{$anioi->anio}}); return false">{{$anioi->anio}}</a></li>
            @endif
          @endforeach
          </ul>
        </span>
      </span>
    </h4>
  </div>
  <div class="table-responsive">
    <table class="table table-striped table-bordered">
      <thead>
        <tr>
          <th>Subregión</th>
          <th>Votos totales</th>
          <th>Votos candidato</th>
          <th>Votos partido</th>
          <th>Votos estimados</th>
          <th>Potencial electoral</th>
        </tr>
      </thead>
      <tbody>
      @foreach($subregiones as $subregion)
        @if($subregion->id != '1' )
        <tr>
          <td><a href="Subregion/{{$subregion->id}}">{{$subregion->nombre}}</a></td>
          <td>{{$subregion->votostotales}}</td>
          <td>{{$subregion->votoscandidato}}</td>
          <td>{{$subregion->votospartido}}</td>
          <td>{{$subregion->votosestimados}}</td>
          <td>{{$subregion->potencialelectoral}}</td>
        </tr>
        @endif
      @endforeach
      </tbody>
    </table>
  </div>
</div>