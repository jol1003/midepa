<?
  include("config.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $ncat = testins($_GET["cat"]); if($ncat != ""){if($ncat == "inmuebles"){$cat = 1;}elseif($ncat == "proyectos"){$cat = 2;}; $fil = " AND categoria='".$cat."'";}
  
  //PAGINANDO
  $pag = testins($_GET["pag"]);
  if(!$pag){$ini = 0; $pag = 1;}else{$ini = ($pag - 1) * 21;}
  $pager = $db->fetchObject("SELECT COUNT(id) AS can FROM inmueble WHERE estado='1' AND usuario_id=".$log.$fil);
  $totreg = $pager->can;
  $totpag = 20;
  $total = ceil($totreg / $totpag);
  $prev = $pag - 1; if($prev==0){$prev = $total;}
  $next = $pag + 1; if($next > $total){$next = 1;}
  if($cat != ""){$upag = URL_PATH."micuenta-".$ncat."/page/";}else{$upag = URL_PATH."micuenta-mispublicaciones/page/";}
  
  $i = 0;
  //echo $log."--".$fil;
  //echo  $_SESSION['idcar'];
  $inms = $db->query("SELECT id, proyecto_id, categoria, nombre,urlnombre,imagen,antiguedad,direccion,areatotalmin,areatotalmax,areaconstruidamin,areaconstruidamax,preciomin,preciomax,preciodolaresmin,preciodolaresmax,adicionales,fechacreacion,fechainicio, fechafin FROM inmueble WHERE usuario_id=".$log.$fil." ORDER BY fechacreacion DESC LIMIT ".$ini.",".$totpag);
  while($inm = mysql_fetch_object($inms)){
	$nlnk = URL_PATH.'inmueble-detalle/'.$inm->id.'/'.$inm->urlnombre;
	$ainm[$i]["nom"] = '<a href="'.$nlnk.'">'.testsho($inm->nombre).'</a>';
	$ainm[$i]["lnk"] = $nlnk;
	$ainm[$i]["lne"] = URL_PATH."micuenta-editarpublicacion/?id=".$inm->id;
	$ainm[$i]["img"] = '<a href="'.$nlnk.'"><img src="'.URW_PATH.'imagenes/inmuebles/thumbs/'.$inm->imagen.'" width="130" height="94" alt="'.str_replace('"','\'',$inm->nombre).'" /></a>';
	if($inm->antiguedad == 1){$ainm[$i]["ant"] = "PROYECTO EN CONSTRUCCION";}elseif($inm->antiguedad == 2){$ainm[$i]["ant"] = "USADO";}else{$ainm[$i]["ant"] = "NUEVO";}
	$ainm[$i]["dir"] = testsho($inm->testsho);
	if($inm->areatotalmax == ""){$ainm[$i]["ato"] = "AT ".$inm->areatotalmin." m²";}else{$ainm[$i]["ato"] = "AT de ".$inm->areatotalmin." m² a ".$inm->areatotalmax." m²";}
	if($inm->areaconstruidamin != ""){if($inm->areaconstruidamax == ""){$ainm[$i]["aco"] = "AC ".$inm->areaconstruidamin." m²";}else{$ainm[$i]["aco"] = "AC de ".$inm->areaconstruidamin." m² a ".$inm->areaconstruidamax." m²";}}
	if($inm->preciomin != ""){
	  if($inm->preciomax == ""){$ainm[$i]["pso"] = "S/. ".$inm->preciomin;}else{$ainm[$i]["pso"] = "de S/. ".$inm->preciomin." a S/. ".$inm->preciomax;}
	  if($inm->preciodolaresmax == ""){$ainm[$i]["pdo"] = "$. ".$inm->preciodolaresmin;}else{$ainm[$i]["pdo"] = "de $. ".$inm->preciodolaresmin." a $. ".$inm->preciodolaresmax;}
	}else{
	  $ainm[$i]["pso"] = "Por consultar"; $ainm[$i]["pdo"] = "Por consultar";
	}
	$ainm[$i]["adi"] = testsho($inm->adicionales);
	$ainm[$i]["fec"] = fecha($inm->fechacreacion, true);
	$ainm[$i]["fecI"] = fecha($inm->fechainicio, true);
	$ainm[$i]["fecF"] = fecha($inm->fechafin, true);
	$ainm[$i]["id"]   = $inm->id;
	$ainm[$i]["proy_id"]   = $inm->categoria;
	
	$i++;
  }
  $caninm = count($ainm);

  $page = "micuenta-mispublicaciones";
  $styles = array("css/paginador.class.css");
  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi">Mi cuenta - Mis publicaciones (Inmuebles)</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
  <div class="sub">Mis publicaciones - <?=$ncat;?></div>
  <div class="frc frn">
<?
  if($caninm > 0){
	for($i=0;$i<$caninm;$i++){?>
	<div class="itm">
	<div class="tit"><h3><?=$ainm[$i]["nom"];?></h3></div>
	<div class="slef"><?=$ainm[$i]["img"];?></div>
	<div class="det">
	<!--<div class="chi"><?=$ainm[$i]["fec"];?> </div>-->
	<div class="bld"><?=$ainm[$i]["dir"];?></div>
	<div><?=$ainm[$i]["ant"];?> </div>
	<div class="bld"><?=$ainm[$i]["ato"];?> <?=$ainm[$i]["aco"];?> </div>
		<? if($ainm[$i]["adi"]!=""){?>
				<div class="chi"><?=$ainm[$i]["adi"];?> </div>
		<? 
		   }
		?>
	<?php
	if($ainm[$i]["proy_id"] == 1){
	?>
	<div class="chi"><?=$ainm[$i]["fecI"];?> - <?=$ainm[$i]["fecF"];?></div>
	<?php
	}
	?>
	<?php
	if($ainm[$i]["proy_id"] == 1){
	?>
	<div class="chi"><a href="<?=URL_PATH?>pago-renovacion.php?id=<?=$ainm[$i]["id"];?>"><strong>Renovar publicaci&oacute;n</strong></a></div>
	<?php
	}
	?>
	</div>
	<div class="flot"></div>
	<div class="dpr">
	<div class="pre"><?=$ainm[$i]["pso"];?></div>
	<div class="prs mod"><?=$ainm[$i]["pdo"];?></div>
	<div class="btn btx"><span><a href="<?=$ainm[$i]["lne"];?>">Editar</a></span></div>
	</div>
	</div>
	<? 
	}
	?>
  	<div class="pagination"><? $str='<a href="'.$upag.$prev.'/" class="prev">&#171; Anterior</a>';if($total > 1){if($total > 15){if($pag >= $total-6){$str.='<a href="'.$upag.'1/">1</a><a href="'.$upag.'2/">2</a>...';for($i=$total-9; $i<=$total; $i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}}}elseif($pag <= 9){for($i=1;$i<=10; $i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}};$str.='...<a href="'.$upag.($total-1).'/">'.($total-1).'</a><a href="'.$upag.$total.'/">'.$total.'</a>';}else{$str.='<a href="'.$upag.'1/">1</a><a href="'.$upag.'2/">2</a>...';for($i=$pag-5; $i<$pag+5; $i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}};$str.='...<a href="'.$upag.($total-1).'/">'.($total-1).'</a><a href="'.$upag.$total.'/">'.$total.'</a>';}}else{for($i=1; $i<=$total; $i++){if($pag == $i){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}}}}else{$str='<span class="current">1</span>';};echo $str.'<a href="'.$upag.$next.'/" class="next">Siguiente &#187;</a>';?></div>
<?
  }?>
  </div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>