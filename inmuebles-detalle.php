<?
  include("config.php");

  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $id = testins($_GET["id"]); if(!is_numeric($id)){header("location:".URL_PATH);}

  $inm = $db->fetchObject("SELECT id,usuario_id,categoria,proyecto_id,inmuebletipo_id,nombre,urlnombre,imagen,antiguedad,direccion,urlyou,areatotalmin,areatotalmax,areaconstruidamin,areaconstruidamax,preciomin,preciomax,preciodolaresmin,preciodolaresmax,dormitoriosmin,dormitoriosmax,banosmin,banosmax,areacomun,adicionales,nedificios,npisos,web,latitud,longitud FROM inmueble WHERE id=".$id." AND estado='1' AND '".date("Y-m-d")."' BETWEEN fechainicio AND fechafin LIMIT 0,1");
  if($inm->id==""){
	header("location:".URL_PATH);
  }else{
  
  $unom = $db->fetchObject("SELECT usuariotipo,email,imagen,telefono1,telefono2,telefono3 FROM usuario WHERE id=".$inm->usuario_id." LIMIT 0,1");
  $utip = $unom->usuariotipo; $umai = $unom->email; $uimg = URW_PATH."imagenes/usuarios/".$unom->imagen; $ute1 = $unom->telefono1; $ute2 = $unom->telefono2; $ute3 = $unom->telefono3;
  if($ute1 != ""){$utel .= $ute1." / ";} if($ute2 != ""){$utel .= $ute2." / ";} if($ute3 != ""){$utel .= $ute3." / ";} $utel = substr($utel, 0, strlen($utel)-3);
  if($utip == 1){$unom = $db->fetchObject("SELECT nombres,apellidos FROM persona WHERE id=".$inm->usuario_id." LIMIT 0,1"); $unom = testsho($unom->nombres." ".$unom->apellidos);}else{$unom = $db->fetchObject("SELECT nombrecomercial,nombrecontacto,direccion,paginaweb FROM empresa WHERE id=".$inm->usuario_id." LIMIT 0,1"); $ucon = testsho($unom->nombrecontacto); $udir = testsho($unom->direccion); $uweb = testsho($unom->paginaweb); $unom = testsho($unom->nombrecomercial);}
  $ncat = $inm->categoria;
  $ntip = $inm->inmuebletipo_id; if($ntip != ""){$ntip = $db->fetchObject("SELECT nombre FROM inmuebletipo WHERE id=".$ntip." LIMIT 0,1"); $ntip = testsho($ntip->nombre);}
  $nnom = testsho($inm->nombre); $ntim = str_replace('"', '\'', $nnom); $nlnk = URL_PATH."inmueble-detalle/".$id."/".$inm->urlnombre;
  $nimg = URW_PATH."imagenes/inmuebles/".$inm->imagen; $ndir = testsho($inm->direccion);
  $nant = $inm->antiguedad; if($nant != ""){if($nant == 1){$nant = "Proyecto en contrucci&oacute;n";}elseif($nant == 2){$nant = "Usado";}else{$nant = "Nuevo";}}
  if($inm->areatotalmax == ""){$nato = $inm->areatotalmin." m²";}else{$nato = "de ".$inm->areatotalmin." m² a ".$inm->areatotalmax." m²";}
  if($inm->areaconstruidamin != ""){if($inm->areaconstruidamax == ""){$naco = $inm->areaconstruidamin." m²";}else{$naco = "de ".$inm->areaconstruidamin." m² a ".$inm->areaconstruidamax." m²";}}
  if($inm->preciomin != ""){if($inm->preciomax == ""){$npso = "S/. ".$inm->preciomin;}else{$npso = "de S/. ".$inm->preciomin." a S/. ".$inm->preciomax;} if($inm->preciodolaresmax == ""){$npdo = "$. ".$inm->preciodolaresmin;}else{$npdo = "de $. ".$inm->preciodolaresmin." a $. ".$inm->preciodolaresmax;}}else{$npso = "Por consultar";  $npdo = "Por consultar"; }
  if($inm->dormitoriosmin != ""){if($inm->dormitoriosmax==""){$ndor = $inm->dormitoriosmin;}else{$ndor = "de ".$inm->dormitoriosmin." a ".$inm->dormitoriosmax;}}else{$ndor = "Sin especificar";}
  if($inm->banosmin != ""){if($inm->banosmax == ""){$nban = $inm->banosmin;}else{$nban = "de ".$inm->banosmin." a ".$inm->banosmax;}}else{$nban = "Sin especificar";}
  $narc = $inm->areacomun; if($narc != ""){$narc = explode(",", $narc); foreach($narc as $arc){$arc = $db->fetchObject("SELECT nombre FROM areacomun WHERE id=".$arc." LIMIT 0,1"); $sarc .= $arc->nombre.", ";}; $narc = substr($sarc, 0, strlen($sarc)-2);}
  $you = $inm->urlyou; $nadi = testsho($inm->adicionales); $lat = testsho($inm->latitud); $lon = testsho($inm->longitud);
  //VISITAS
  $nomcookie = "det".$id; if(!isset($_COOKIE[$nomcookie])){setcookie($nomcookie,"vis",time()+86400); $upd = $db->query("UPDATE inmueblevisita SET visitas=visitas+1 WHERE id=".$id." LIMIT 1");}
  $vis = $db->fetchObject("SELECT visitas FROM inmueblevisita WHERE id=".$id." LIMIT 0,1"); $vis = $vis->visitas;
  //GALERIA
  $gals = $db->query("SELECT imagen FROM inmueblegaleria WHERE inmueble_id=".$id." ORDER BY id ASC");
  while($gal = mysql_fetch_object($gals)){$agal[] = '<img src="'.URW_PATH.'imagenes/inmuebles/thumbs/'.$gal->imagen.'" width="106" height="82" alt="'.$ntim.'" />';}
  $cangal = count($agal);
  //SI ES PROYECTO TIENE INMUEBLES
  if($ncat == 2){
	$i = 0;
  	$inms = $db->query("SELECT id,nombre,urlnombre,imagen,antiguedad,direccion,areatotalmin,areatotalmax,areaconstruidamin,areaconstruidamax,preciomin,preciomax,preciodolaresmin,preciodolaresmax,adicionales,fechacreacion FROM inmueble WHERE estado='1' AND categoria='1' AND proyecto_id=".$id);
  	while($inm = mysql_fetch_object($inms)){
	  $lnk = URL_PATH.'inmueble-detalle/'.$inm->id.'/'.$inm->urlnombre;
	  $ainm[$i]["nom"] = '<a href="'.$nlnk.'">'.testsho($inm->nombre).'</a>';
 	  $ainm[$i]["lnk"] = $lnk;
	  $ainm[$i]["img"] = '<a href="'.$lnk.'"><img src="'.URW_PATH.'imagenes/inmuebles/thumbs/'.$inm->imagen.'" width="130" height="94" alt="'.str_replace('"','\'',$inm->nombre).'" /></a>';
	  if($inm->antiguedad == 1){$ainm[$i]["ant"] = "PROYECTO EN CONSTRUCCION";}elseif($inm->antiguedad == 2){$ainm[$i]["ant"] = "USADO";}else{$ainm[$i]["ant"] = "NUEVO";}
	  $ainm[$i]["dir"] = testsho($inm->testsho);
	  $ainm[$i]["ato"] = "AT ".$inm->areatotalmin." m²";
	  if($inm->areaconstruidamin != ""){$ainm[$i]["aco"] = "AC ".$inm->areaconstruidamin." m²";}
	  if($inm->preciomin != ""){
	  	$ainm[$i]["pso"] = "S/. ".$inm->preciomin; $ainm[$i]["pdo"] = "$. ".$inm->preciodolaresmin;
	  }else{
	  	$ainm[$i]["pso"] = "Por consultar"; $ainm[$i]["pdo"] = "Por consultar";
	  }
	  $ainm[$i]["adi"] = testsho($inm->adicionales);
	  $ainm[$i]["fec"] = fecha($inm->fechacreacion, true);
	  $i++;
  	}
  	$caninm = count($ainm);
  }
  
  }
  
  $page = "inmuebles";
  $pagedet = 1;
  $styles = array("js/carousel/css/jcarousel.css");
  $scripts = array("js/carousel/jquery.jcarousel.js");
  $javascript = '$(document).ready(function(){var latlng=new google.maps.LatLng('.$lat.','.$lon.');var myOptions={zoom:8,center:latlng,mapTypeId:google.maps.MapTypeId.ROADMAP};var map=new google.maps.Map(document.getElementById("map_canvas"),myOptions);var marker=new google.maps.Marker({position:latlng,map:map,title:"Hello World!"});$("#mycarousel").jcarousel();});';
  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi"><a href="">Inmuebles</a> | <a href="">Otro</a></div>
<div class="ica"><h1><?=$nnom;?></h1></div>
<div class="izq">
  <div class="itg itn">
  	<div id="img" class="img"><img src="<?=$nimg;?>" /></div>
  	<div class="bkc">
  	  <div class="bkci">
      	<div class="bld"><?=$ndir;?></div>
      	<div class="itm"><div class="pre"><?=$npso;?></div><div class="prs"><?=$npdo;?></div></div>
      	<div class="itm chi">Este inmueble ha sido visto <?=$vis;?> veces</div>
      	<div class="itm"><a href="" class="prt">Imprimir</a><!--<a href="" class="env">Enviar</a>--></div>
      	<div class="itm itp"><div class="fb-like" data-send="false" data-layout="button_count" data-width="450" data-show-faces="false"></div></div>
      	<div class="red"><div class="mod">Compartir:</div><span class="twtg"><a href=""></a></span><span class="fbkg"><a class="fb-share" onclick="window.open('http://www.facebook.com/sharer.php?s=100'+'&p[url]=<?=$nlnk;?>'+'&p[title]=<?=$ntim;?>'+'&p[images][0]=<?=$nimg;?>'+'&p[summary]=<?=$nadi." ".$narc;?>','mywindow','width=800,height=500,resizable=yes');" href="javascript:void(0);"></a></span><span class="goog"><a href=""></a></span><span class="gnrg"><a href=""></a></span><span class="maig"><a href=""></a></span><div class="flot"></div></div>
      </div>
  	</div>
  	<div class="flot"></div>
<? if($cangal > 1){?><ul id="mycarousel" class="jcarousel-skin-det"><? foreach($agal as $gal){?><li><?=$gal;?></li><? }?></ul><? }?>
  </div>
  <div class="itg"><div class="suv">Descripci&oacute;n del proyecto</div><div class="mad"><?=$nadi;?></div><div class="itt"><span class="lbd">Tipo de inmueble:</span><span class="flef"><?=$ntip;?></span><div class="flot"></div></div><div class="itt"><span class="lbd">Direcci&oacute;n:</span><span class="flef"><?=$ndir;?></span><div class="flot"></div></div><div class="itt"><span class="lbd">&Aacute;rea total (m²):</span><span class="flef"><?=$nato;?></span><div class="flot"></div></div><? if($naco != ""){?><div class="itt"><span class="lbd">&Aacute;rea construida (m²):</span><span class="flef"><?=$naco;?></span><div class="flot"></div></div><? }?><? if($nant != ""){?><div class="itt"><span class="lbd">Antigüedad:</span><span class="flef"><?=$nant;?></span><div class="flot"></div></div><? }?><div class="itt"><span class="lbd">Precio:</span><span class="flef"><span class="prs"><?=$npso;?></span> (<?=$npdo;?>)</span><div class="flot"></div></div><div class="itt"><span class="lbd">Dormitorios:</span><span class="flef"><?=$ndor;?></span><div class="flot"></div></div><div class="itt"><span class="lbd">Ba&ntilde;os:</span><span class="flef"><?=$nban;?></span><div class="flot"></div></div><? if($narc != ""){?><div class="itt"><span class="lbd">&Aacute;reas comunes:</span><span class="flef"><?=$narc;?></span><div class="flot"></div></div><? }?></div>
<?
  if($you!=""){?><div class="mod"><iframe width="300" height="170" src="http://www.youtube.com/embed/<?=$you;?>" frameborder="0" allowfullscreen></iframe></div>
<?
  }
  if($ncat == 2){?>
  <div class="its"><div class="icc">Inmuebles de este proyecto</div><? if($caninm > 0){for($i=0;$i<$caninm;$i++){?><div class="itm"><div class="tit"><h3><?=$ainm[$i]["nom"];?></h3></div><div class="slef"><?=$ainm[$i]["img"];?></div><div class="det"><div class="chi"><?=$ainm[$i]["fec"];?></div><div class="bld"><?=$ainm[$i]["dir"];?></div><div><?=$ainm[$i]["ant"];?></div><div class="bld"><?=$ainm[$i]["ato"];?> <?=$ainm[$i]["aco"];?></div><? if($ainm[$i]["adi"]!=""){?><div class="chi"><?=$ainm[$i]["adi"];?></div><? }?></div><div class="flot"></div><div class="dpr"><div class="pre"><?=$ainm[$i]["pso"];?></div><div class="prs mod"><?=$ainm[$i]["pdo"];?></div><div class="btn btx"><span><a href="<?=$ainm[$i]["lnk"];?>">Ver m&aacute;s</a></span></div></div></div><? }}?></div>
<?
  }?>
  <div class="fb-comments its" data-href="<?=$nlnk;?>" data-num-posts="10" data-width="504"></div>
</div>
<div class="der">
  <div class="mod"><div class="bkm"><div class="bkmi"><div class="mod"><div class="icg">Datos del contacto</div><? if($utip==1){?><div class="slef"><img src="<?=$uimg;?>" width="76" height="76" /></div><div class="dde chi"><?=$unom;?></div><div class="flot"></div><? }else{?><div class="mod"><img src="<?=$uimg;?>" /></div><div class="chi"><div><?=$ucon;?></div><div><?=$unom;?></div><div><?=$udir;?></div></div><? }?></div><? if($utel!=""){?><div class="mod"><div class="ddl">Tel&eacute;fonos:</div><div class="ddd"><?=$utel;?></div><div class="flot"></div></div><? }?><!--<div class="mod"><div class="ddl">Email:</div><div class="ddd"><?=$umai;?></div><div class="flot"></div></div>--><? if($uweb!=""){?><div class="mod"><div class="ddl">Pag. web:</div><div class="ddd"><a href="<?=$uweb;?>" target="_blank"><?=$uweb;?></a></div><div class="flot"></div></div><? }?></div></div></div>
  <div class="mod"><div class="ice">Ubicaci&oacute;n en el mapa</div><div id="map_canvas" style="width:310px;height:300px;"></div></div>
  <!--<div class="mod">
  	[Zona del tabber]
  </div>-->
  <div class="mod"><div id="pasos"></div><script type="text/javascript">swfobject.embedSWF("<?=URL_PATH;?>assets/swf/3pasos.swf","pasos","310","300","9.0.0","<?=URL_PATH;?>assets/js/swfobject/expressInstall.swf",{},{wmode:"transparent"},{});</script></div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>