@if($import)
<div class="row" style="margin:0px 0px 10px 0px">
  <form action="../Importar/{{$panelsup[0]}}" method="POST" enctype="multipart/form-data">
    <input class="pull-left btn btn-default" style="border-radius:2px" type="file" name="archivo" id="archivo">
    <button class="pull-left btn btn-default" style="margin-left:5px; padding: 6px 6px 6px 6px" type="submit">
      <i class="fa fa-cloud-upload fa-lg" aria-hidden="true"></i>
    </button>
    <input type="hidden" name="ruta" value="{{url()->current()."?".http_build_query($_GET)}}">
    <input type="hidden" name="_token" value="{{ csrf_token() }}">
  </form>
</div>
@endif
<div class="row">
  <div class="pull-left" style="margin-left: 15px;">
    {!!Form::open(['action' => 'ExportExcelController@'.$panelsup[2], 'method' => 'GET', 'class' => 'pull-left', 'style' => 'margin-bottom: 5px; margin-right: 5px;'])!!}
      <button type="submit" class="btn btn-default" style="padding-right:7px; padding-left:7px"><i class="fa fa-download fa-lg" aria-hidden="true"></i></button>
    {!!Form::close()!!}
    <p class="pull-right">
      <button type="button" class="btn btn-default" data-toggle="modal" data-target="#ModalCrear"><i class="fa fa-plus" aria-hidden="true"></i> {{$panelsup[3]}}</button>
    </p>
  </div>
  <div class="pull-right" style="margin-right: 15px;">
    {!!Form::open(['action' => $panelsup[1].'Controller@index', 'method' => 'GET'])!!}
      <div class="form-group">
        {{Form::text('q', '', ['class' => 'form-control', 'placeholder' => 'Buscar'])}}
      </div>
    {!!Form::close()!!}
  </div>
</div>