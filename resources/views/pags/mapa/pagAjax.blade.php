<div id="pagS<?php echo $nombreZona ?>">
  @if($totPags > 1)
  <ul class="pagination pull-left" style="margin-bottom: 0px">
  <?php
  for ($i=0; $i < ($totPags+2) && $i < 9; $i++) {
    $var = true;
    switch ($var) {
      case ($i == 0):
        if ($page == 1) {
          $num = $totPags;
        } else {
          $num = $page - 1;
        }
        echo "<li><a href=# onclick='conseguir".$nombreZona."(".$num.", ".$rows.$busq."); return false'><</a></li>";
        break;
      case ($i == ($totPags + 1) || $i == 8):
        if ($page == $totPags) {
          $num = 1;
        } else {
          $num = $page + 1;
        }
        echo "<li><a href=# onclick='conseguir".$nombreZona."(".$num.", ".$rows.$busq."); return false'>></a></li>";
        break;
      case ($i == 1):
        $num = 1;
        echo "<li class=".($i == $page ? "'active'" : " " )."><a href=# onclick='conseguir".$nombreZona."(".$num.", ".$rows.$busq."); return false'>".($i)."</a></li>";
        break;
      case ($i == $totPags || $i == 7):
        $num = $totPags;
        echo "<li class=".($totPags == $page ? "'active'" : " " )."><a href=# onclick='conseguir".$nombreZona."(".$num.", ".$rows.$busq."); return false'>".($totPags)."</a></li>";
        break;
      case ((($i == 2 && $page > 4) || ($i == 6 && $page < $totPags - 3)) && $totPags > 7):
        echo "<li><a>...</a></li>";
        break;
      case (($page > 4 && $page < $totPags - 3) && $i == 3):
        $num = $page - 1;
        echo "<li><a href=# onclick='conseguir".$nombreZona."(".$num.", ".$rows.$busq."); return false'>".($page - 1)."</a></li>";
        break;
      case (($page > 4 && $page < $totPags - 3) && $i == 4):
        $num = $page;
        echo "<li class='active'><a>".($page)."</a></li>";
        break;
      case (($page > 4 && $page < $totPags - 3) && $i == 5):
        $num = $page + 1;
        echo "<li><a href=# onclick='conseguir".$nombreZona."(".$num.", ".$rows.$busq."); return false'>".($page + 1)."</a></li>";
        break;
      case ($page > $totPags - 4 && $totPags > 7 && $i > 2):
        $num = $i + $totPags - 7;
        echo "<li class=".($i + $totPags - 7 == $page ? "'active'" : " " )."><a href=# onclick='conseguir".$nombreZona."(".$num.", ".$rows.$busq."); return false'>".($i + $totPags - 7)."</a></li>";
        break;
      default:
        $num = $i;
        echo "<li class=".($i == $page ? "'active'" : " " )."><a href=# onclick='conseguir".$nombreZona."(".$num.", ".$rows.$busq."); return false'>".($i)."</a></li>";
        break;
    }
  }
  ?>
  </ul>
  <?php endif; ?>
  <?php if ($totRows > 5): ?>
  <ul class="pagination pull-right vcenter-parent" style="margin-bottom: 0px">
    <span class="pagination-info pull-left">Filas por página:&nbsp</span>
    <span style="display: inline-block;">
      <span class="btn-group dropup pull-left" style="position: relative; display: inline-block; vertical-align: middle;">
        <button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
          <?php echo $rows ?>&nbsp<span class="caret"></span>
        </button>
        <?php $indices = array(5, 10, 25, 50, 100); ?>
        <ul class="dropdown-menu" style="margin: 0px; padding: 0px; border-radius: 2px">
          <?php if ($rows != $indices[0]): ?>
          <li><a href=# style="margin: 3px; padding: 3px;" onclick='conseguir<?php echo $nombreZona."(".$page.",".$indices[0].$busq ?>); return false'><?php echo $indices[0] ?></a></li>
          <?php endif; ?>
          <?php if ($rows != $indices[1]): ?>
          <li><a href=# style="margin: 3px; padding: 3px;" onclick='conseguir<?php echo $nombreZona."(".$page.",".$indices[1].$busq ?>); return false'><?php echo $indices[1] ?></a></li>
          <?php endif; ?>
          <?php if ($rows != $indices[2] && $totRows > 10): ?>
          <li><a href=# style="margin: 3px; padding: 3px;" onclick='conseguir<?php echo $nombreZona."(".$page.",".$indices[2].$busq ?>); return false'><?php echo $indices[2] ?></a></li>
          <?php endif; ?>
          <?php if ($rows != $indices[3] && $totRows > 25): ?>
          <li><a href=# style="margin: 3px; padding: 3px;" onclick='conseguir<?php echo $nombreZona."(".$page.",".$indices[3].$busq ?>); return false'><?php echo $indices[3] ?></a></li>
          <?php endif; ?>
          <?php if ($rows != $indices[4] && $totRows > 50) : ?>
          <li><a href=# style="margin: 3px; padding: 3px;" onclick='conseguir<?php echo $nombreZona."(".$page.",".$indices[4].$busq ?>); return false'><?php echo $indices[4] ?></a></li>
          <?php endif; ?>
        </ul>
      </span>
    </span>
    <?php
    if ($page != $totPags) {
      $ultFilaPag = (($page-1)*$rows)+$rows;
    } else {
      $ultFilaPag = $totRows;
    }
    ?>
    <span>&nbsp|| Mostrando <?php echo ((($page-1)*$rows)+1)." - ".$ultFilaPag; ?> de <?php echo $totRows ?> filas en total</span>
  </ul>
  <?php endif; ?>
</div>