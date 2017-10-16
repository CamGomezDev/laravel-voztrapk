@if($totRows > 5)
<ul class="pagination pull-right vcenter-parent">
  <span class="pagination-info pull-left">Filas por página:&nbsp</span>
  <span style="display: inline-block;">
    <span class="btn-group dropup pull-left" style="position: relative; display: inline-block; vertical-align: middle;">
      <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
        {{$rows}}&nbsp<span class="caret"></span>
      </button>
      <?php $indices = array(5, 10, 25, 50, 100); ?>
      <ul class="dropdown-menu" style="margin: 0px; padding: 0px; border-radius: 2px">
        @if($rows != $indices[0])
        <?php $get = $_GET; $get['rows'] = $indices[0]; $get_result = http_build_query($get); ?>
        <li><a href="{{url()->current().'?'.$get_result}}" style="margin: 3px; padding: 3px;">{{$indices[0]}}</a></li>
        @endif
        @if($rows != $indices[1])
        <?php $get = $_GET; $get['rows'] = $indices[1]; $get_result = http_build_query($get); ?>
        <li><a href="{{url()->current().'?'.$get_result}}" style="margin: 3px; padding: 3px;">{{$indices[1]}}</a></li>
        @endif
        @if($rows != $indices[2] && $totRows > 10)
        <?php $get = $_GET; $get['rows'] = $indices[2]; $get_result = http_build_query($get); ?>
        <li><a href="{{url()->current().'?'.$get_result}}" style="margin: 3px; padding: 3px;">{{$indices[2]}}</a></li>
        @endif
        @if($rows != $indices[3] && $totRows > 25)
        <?php $get = $_GET; $get['rows'] = $indices[3]; $get_result = http_build_query($get); ?>
        <li><a href="{{url()->current().'?'.$get_result}}" style="margin: 3px; padding: 3px;">{{$indices[3]}}</a></li>
        @endif
        @if($rows != $indices[4] && $totRows > 50) 
        <?php $get = $_GET; $get['rows'] = $indices[4]; $get_result = http_build_query($get); ?>
        <li><a href="{{url()->current().'?'.$get_result}}" style="margin: 3px; padding: 3px;">{{$indices[4]}}</a></li>
        @endif 
      </ul>
    </span>
  </span>
  <?php
  if (isset($_GET['page'])) {
    $pagNum = $_GET['page'];
  } else {
    $pagNum = 1;
  }
  
  $totPags = ceil($totRows / $rows);
  if ($pagNum != $totPags) {
    $ultFilaPag = (($pagNum-1)*$rows)+$rows;
  } else {
    $ultFilaPag = $totRows;
  }
  ?>
  <span>&nbsp|| Mostrando <?php echo ((($pagNum-1)*$rows)+1)." - ".$ultFilaPag; ?> de <?php echo $totRows ?> filas en total</span>
</ul>
@endif