<?
  include("config.php");
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $ncat = testins($_GET["cat"]); if($ncat == "inmuebles"){$cat = 1;}elseif($ncat == "proyectos"){$cat = 2;}else{header("location:".URL_PATH);}

  //PAGINANDO
  $pag = testins($_GET["pag"]);
  if(!$pag){$ini = 0; $pag = 1;}else{$ini = ($pag - 1) * 21;}
  $pager = $db->fetchObject("SELECT COUNT(id) AS can FROM inmueble WHERE estado='1' AND categoria='".$cat."' AND '".date("Y-m-d")."' BETWEEN fechainicio AND fechafin");
  $totreg = $pager->can;
  $totpag = 20;
  $total = ceil($totreg / $totpag);
  $prev = $pag - 1; if($prev==0){$prev = $total;}
  $next = $pag + 1; if($next > $total){$next = 1;}
  $upag = URL_PATH.$ncat."/page/";
  
  $i = 0;
  $inms = $db->query("SELECT id,nombre,urlnombre,imagen,antiguedad,direccion,areatotalmin,areatotalmax,areaconstruidamin,areaconstruidamax,preciomin,preciomax,preciodolaresmin,preciodolaresmax,adicionales,fechacreacion FROM inmueble WHERE estado='1' AND categoria='".$cat."' AND '".date("Y-m-d")."' BETWEEN fechainicio AND fechafin ORDER BY fechacreacion DESC LIMIT ".$ini.",".$totpag);
  while($inm = mysql_fetch_object($inms)){
	$nlnk = URL_PATH.'inmueble-detalle/'.$inm->id.'/'.$inm->urlnombre;
	$ainm[$i]["nom"] = '<a href="'.$nlnk.'">'.testsho($inm->nombre).'</a>';
	$ainm[$i]["lnk"] = $nlnk;
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
	$i++;
  }
  $caninm = count($ainm);
  
  //JSON DE LOS DISTRITOS
  $dat = file_get_contents(DIR_PATH."assets/js/data/json_prodis.json");
  
  $page = $ncat;
  $styles = array("css/paginador.class.css","js/token/css/token-input-advanced.css");
  $scripts = array("js/token/jquery.tokeninput.min.js");
  $javascript = '$(document).ready(function(){$("#txtbus").tokenInput('.$dat.',{theme:"advanced",tokenLimit:3,resultsLimit:50});});';
  include(DIR_PATH."assets/includes/header.php");?>
<div class="izq">
  <div class="bkg">
  	<div class="bkgi">
      <div class="ica">Encuentra tu inmueble aqu&iacute;:</div>
      <div class="bim">
      <form id="frmBus" name="frmBus" method="get" action="<?=URL_PATH;?>busqueda-inmuebles/">
        <div class="mad"><div class="slef"><select name="cboope" class="txt"><option value="1">Compra</option><option value="2">Alquiler</option></select></div><div class="flef"><select name="cbotip" class="txt"><option value="">[Todos los tipos]</option><option value="1">Casa</option><option value="2">Departamento</option><option value="3">Habitaci&oacute;n</option><option value="4">Terreno</option></select></div><div class="flot"></div></div>
        <div class="bux"><div class="bus"><input type="text" name="txtbus" id="txtbus" /></div><div class="btn btx"><span><input type="submit" id="btnbus" value="Buscar" /></span></div><div class="flot"></div></div>
      </form>
      </div>
    </div>
  </div>
<?
  if($caninm > 0){
	for($i=0;$i<$caninm;$i++){?>
  <div class="itm"><div class="tit"><h3><?=$ainm[$i]["nom"];?></h3></div><div class="slef"><?=$ainm[$i]["img"];?></div><div class="det"><div class="chi"><?=$ainm[$i]["fec"];?></div><div class="bld"><?=$ainm[$i]["dir"];?></div><div><?=$ainm[$i]["ant"];?></div><div class="bld"><?=$ainm[$i]["ato"];?> <?=$ainm[$i]["aco"];?></div><? if($ainm[$i]["adi"]!=""){?><div class="chi"><?=$ainm[$i]["adi"];?></div><? }?></div><div class="flot"></div><div class="dpr"><div class="pre"><?=$ainm[$i]["pso"];?></div><div class="prs mod"><?=$ainm[$i]["pdo"];?></div><div class="btn btx"><span><a href="<?=$ainm[$i]["lnk"];?>">Ver m&aacute;s</a></span></div></div></div>
<?	}?>
  <div class="pagination"><? $str='<a href="'.$upag.$prev.'/" class="prev">&#171; Anterior</a>';if($total>1){if($total>15){if($pag>=$total-6){$str.='<a href="'.$upag.'1/">1</a><a href="'.$upag.'2/">2</a>...';for($i=$total-9;$i<=$total;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}}}elseif($pag<=9){for($i=1;$i<=10;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}};$str.='...<a href="'.$upag.($total-1).'/">'.($total-1).'</a><a href="'.$upag.$total.'/">'.$total.'</a>';}else{$str.='<a href="'.$upag.'1/">1</a><a href="'.$upag.'2/">2</a>...';for($i=$pag-5;$i<$pag+5;$i++){if($i==$pag){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}};$str.='...<a href="'.$upag.($total-1).'/">'.($total-1).'</a><a href="'.$upag.$total.'/">'.$total.'</a>';}}else{for($i=1;$i<=$total;$i++){if($pag==$i){$str.='<span class="current">'.$pag.'</span>';}else{$str.='<a href="'.$upag.$i.'/">'.$i.'</a>';}}}}else{$str='<span class="current">1</span>';};echo $str.'<a href="'.$upag.$next.'/" class="next">Siguiente &#187;</a>';?></div>
<?
  }?>
</div>
<div class="der">
  <div class="mod"><img src="<?=URL_PATH;?>assets/img/img1.jpg" /></div>
  <!--<div class="mod">
    <div class="bkm">
      <div class="bkmi">
      	<div class="ici">B&uacute;squeda avanzada</div>
        
      </div>
    </div>
  </div>
  <div class="mod">
  	[Zona del tabber]
  </div>-->
  <div class="mod"><div id="pasos"></div><script type="text/javascript">swfobject.embedSWF("<?=URL_PATH;?>assets/swf/3pasos.swf","pasos","310","300","9.0.0","<?=URL_PATH;?>assets/js/swfobject/expressInstall.swf",{},{wmode:"transparent"},{});</script></div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>