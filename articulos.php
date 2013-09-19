<?
  include("config.php");
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  //PAGINANDO
  $pag = testins($_GET["pag"]);
  if(!$pag){$ini = 0; $pag = 1;}else{$ini = ($pag - 1) * 21;}
  $pager = $db->fetchObject("SELECT COUNT(id) AS can FROM articulo WHERE estado='1'");
  $totreg = $pager->can;
  $totpag = 20;
  $total = ceil($totreg / $totpag);
  $prev = $pag - 1; if($prev==0){$prev = $total;}
  $next = $pag + 1; if($next > $total){$next = 1;}
  $upag = URL_PATH."articulos/page/";
  
  $i = 0;
  $inms = $db->query("SELECT id,titulo,urltitulo,sumilla,imagen FROM articulo WHERE estado='1' ORDER BY fechacreacion DESC LIMIT ".$ini.",".$totpag);
  while($inm = mysql_fetch_object($inms)){
	$nlnk = URL_PATH.'articulo-detalle/'.$inm->id.'/'.$inm->urltitulo;
	$ainm[$i]["tit"] = '<a href="'.$nlnk.'">'.testsho($inm->titulo).'</a>';
	$ainm[$i]["sml"] = testsho($inm->sumilla);
	$ainm[$i]["lnk"] = $nlnk;
	$ainm[$i]["img"] = '<a href="'.$nlnk.'"><img src="'.URW_PATH.'imagenes/articulos/thumbs/'.$inm->imagen.'" width="130" height="94" alt="'.str_replace('"','\'',$inm->titulo).'" /></a>';
	$i++;
  }
  $caninm = count($ainm);
  
  $page = "";
  $styles = array("css/paginador.class.css");
  include(DIR_PATH."assets/includes/header.php");?>
<div class="izq">
<?
  if($caninm > 0){
	for($i=0;$i<$caninm;$i++){?><div class="itm"><div class="slef"><?=$ainm[$i]["img"];?></div><div class="det"><div class="tit"><h3><?=$ainm[$i]["tit"];?></h3></div><div class="mod"><?=$ainm[$i]["sml"];?></div><div class="btn btx"><span><a href="<?=$ainm[$i]["lnk"];?>">Ver m&aacute;s</a></span></div></div><div class="flot"></div></div><?	}?>
  <div class="pagination"><? $str='<a href="'.$upag.$prev.'/" class="prev">&#171; Anterior</a>';if($total>1){if($total>15){if($pag>=$total-6){$str.='<a href="'.$upag.'1/">1</a><a href="'.$upag.'2/">2</a>...';for($i=$total-9;$i<=$total;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}}}elseif($pag<=9){for($i=1;$i<=10;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}};$str.='...<a href="'.$upag.($total-1).'/">'.($total-1).'</a><a href="'.$upag.$total.'/">'.$total.'</a>';}else{$str.='<a href="'.$upag.'1/">1</a><a href="'.$upag.'2/">2</a>...';for($i=$pag-5;$i<$pag+5;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}};$str.='...<a href="'.$upag.($total-1).'/">'.($total-1).'</a><a href="'.$upag.$total.'/">'.$total.'</a>';}}else{for($i=1;$i<=$total;$i++){if($pag==$i){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}}}}else{$str='<span class="current">1</span>';};echo $str.'<a href="'.$upag.$next.'/" class="next">Siguiente &#187;</a>';?></div>
<?
  }?>
</div>
<div class="der">
  <div class="mod"><img src="<?=URL_PATH;?>assets/img/img1.jpg" /></div>
  <div class="mod"><img src="<?=URL_PATH;?>assets/img/img4.jpg" /></div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>