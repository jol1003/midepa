<?
  include("config.php");

  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  $xml = new DomDocument();
  
  //PROYECTOS
  //$xml->load(DIR_PATH."assets/xml/xml_proyectos_home.xml"); 
  $i = 0;
  $prys = $db->query("SELECT id,nombre,urlnombre,imagen,preciomin,preciodolaresmin FROM inmueble WHERE categoria='1' AND estado='1' AND '".date("Y-m-d")."' BETWEEN fechainicio AND fechafin LIMIT 0,9");
  while($pry = mysql_fetch_object($prys)){
	$apry[$i]["tit"] = testsho($pry->nombre);
	$apry[$i]["tim"] = str_replace('"', '\'', $pry->nombre);
	$apry[$i]["lnk"] = URL_PATH."inmueble-detalle/".$pry->id."/".$pry->urlnombre;
	$apry[$i]["img"] = URW_PATH."imagenes/inmuebles/".$pry->imagen;
	if($pry->preciomin==""){$apry[$i]["pso"]="Por consultar";}else{$apry[$i]["pso"]=$pry->preciomin;};
	if($pry->preciodolaresmin==""){$apry[$i]["pdo"]="Por consultar";}else{$apry[$i]["pdo"]=$pry->preciodolaresmin;};
	$i++;
  }
  $canpry = count($apry);
  //INMUEBLES
  $xml->load(DIR_PATH."assets/xml/xml_inmuebles_home.xml");
  $inms = $xml->getElementsByTagName("itm");foreach($inms as $inm){$ainm[]=$inm->nodeValue;};$caninm = count($ainm);
  //ARTICULOS
  $i = 0; $xml->load(DIR_PATH."assets/xml/xml_articulos_home.xml");
  $arts = $xml->getElementsByTagName("itm");foreach($arts as $art){$aart[$i]["tit"]=$art->getElementsByTagName("tit")->item(0)->nodeValue; $aart[$i]["sml"] = $art->getElementsByTagName("sml")->item(0)->nodeValue; $aart[$i]["img"] = $art->getElementsByTagName("img")->item(0)->nodeValue;$i++;}$canart = count($aart);
  //EMPRESAS
  $xml->load(DIR_PATH."assets/xml/xml_empresas_home.xml");
  //CONSTRUCTORAS
  $emps = $xml->getElementsByTagName("constructoras")->item(0); $emps = $emps->getElementsByTagName("itm");foreach($emps as $emp){$acon[] = $emp->nodeValue;};$cancon = count($acon);
  //AGENTES
  $emps = $xml->getElementsByTagName("agentes")->item(0); $emps = $emps->getElementsByTagName("itm");foreach($emps as $emp){$aage[] = $emp->nodeValue;};$canage = count($aage);
  //JSON DE LOS DISTRITOS
  $dat = file_get_contents(DIR_PATH."assets/js/data/json_prodis.json");
  
  $page = "inicio";
  $styles = array("js/carousel/css/jcarousel.css","js/token/css/token-input-advanced.css");
  $scripts = array("js/carousel/jquery.jcarousel.js","js/token/jquery.tokeninput.min.js");
  $javascript = 'var c=0,sw=0;function mycarousel_initCallback(carousel){';
  if($canpry < 3){$javascript .= 'carousel.stopAuto();};';}else{$javascript .= '$(".jcarousel-control a").bind("click",function(){carousel.stopAuto();c=$(this).attr("rel");var t=1+(c*3);carousel.scroll($.jcarousel.intval(t));$(".jcarousel-control a").attr("class","");$(this).attr("class","sel");carousel.startAuto();sw=1;return false;});$("#mycarousel-prev").bind("click",function(){carousel.prev();carousel.stopAuto();$(".jcarousel-control a").attr("class","");c--;if(c==-1){c=2;};$(".jcarousel-control").find("a").eq(c).attr("class","sel");carousel.startAuto();sw=1;return false;});$("#mycarousel-next").bind("click",function(){carousel.next();carousel.stopAuto();$(".jcarousel-control a").attr("class","");c++;if(c==3){c=0;};$(".jcarousel-control").find("a").eq(c).attr("class","sel");carousel.startAuto();sw=1;return false;});carousel.clip.hover(function(){carousel.stopAuto();},function(){carousel.startAuto();});};';};$javascript .= 'function mycarousel_itemFirstInCallback(carousel,item,idx,state){if(state=="init"||sw==1){}else{$(".jcarousel-control a").attr("class","");c++;if(c==3){c=0;};$(".jcarousel-control").find("a").eq(c).attr("class","sel");};sw=0;}; $(document).ready(function(){$("#mycarousel").jcarousel({auto:3,initCallback:mycarousel_initCallback,itemFirstInCallback:mycarousel_itemFirstInCallback,wrap:"circular",buttonNextHTML:null,buttonPrevHTML:null});$("#txtbus").tokenInput('.$dat.',{theme:"advanced",tokenLimit:3,resultsLimit:50});});';
  include(DIR_PATH."assets/includes/header.php");?>
  <script type="text/javascript" charset="utf-8">swfobject.embedSWF("<?=URL_PATH;?>assets/swf/3pasos.swf","pasos","310","300","9.0.0","<?=URL_PATH;?>assets/js/swfobject/expressInstall.swf",{},{wmode:"transparent"},{});</script>
<div class="itm itn">
  <div class="izq">
  	<div class="bkg">
  	  <div class="bkgi">
      	<div class="ica">Encuentra tu inmueble aqu&iacute;:</div>
      	<div class="bim">
        <form id="frmBus" name="frmBus" method="get" action="<?=URL_PATH;?>busqueda-inmuebles/"><div class="mad"><div class="slef"><select name="cboope" class="txt"><option value="1">Compra</option><option value="2">Alquiler</option></select></div><div class="flef"><select name="cbotip" class="txt"><option value="">[Todos los tipos]</option><option value="1">Casa</option><option value="2">Departamento</option><option value="3">Habitaci&oacute;n</option><option value="4">Terreno</option></select></div><div class="flot"></div></div><div class="mad"><div class="bus"><input type="text" name="txtbus" id="txtbus" /></div></div><div class="mad"><div class="lbl">N de dormitorios</div><div class="flef"><select name="cbodor" class="txt"><option value="0">0 o m&aacute;s</option><option value="1">1 o m&aacute;s</option><option value="2">2 o m&aacute;s</option><option value="3">3 o m&aacute;s</option><option value="4">4 o m&aacute;s</option><option value="5">5 o m&aacute;s</option></select></div><div class="flot"></div></div><div class="lbl">Desde</div><div class="lbl">S/. </div><div class="slef"><input type="text" name="txtpmn" id="txtpmn" maxlength="8" class="txt" /></div><div class="lbl">-</div><div class="lbl">hasta</div><div class="lbl">S/. </div><div class="flef"><input type="text" name="txtpmx" id="txtpmx" maxlength="8" class="txt" /></div><div class="btn btx"><span><input type="submit" id="btnbus" value="Buscar" /></span></div><div class="flot"></div></form>
        </div>
      </div>
  	</div>
  </div>
  <div class="der">
  	<img src="<?=URL_PATH;?>assets/img/img1.jpg" />
  </div>
  <div class="flot"></div>
</div>
<div class="itm itp">
  <div id="mycarousel" class="jcarousel-skin">
  	<div class="icb flef">Proyectos destacados</div><div class="jcarousel-control"><a href="#" class="sel" rel="0"></a><a href="#" rel="1"></a><a href="#" rel="2"></a></div><div class="flot"></div>
  	<a href="#" id="mycarousel-prev"></a><ul><? if($canpry > 0){for($i=0;$i<$canpry;$i++){if($i==0||$i==3||$i==6){$cla="bga";}elseif($i==1||$i==4||$i==7){$cla="bgb";}elseif($i==2||$i==5||$i==8){$cla="bgc";}?><li><a href="<?=$apry[$i]["lnk"];?>" class="vtn">Ver m&aacute;s</a><div><a href="<?=$apry[$i]["lnk"];?>"><img src="<?=$apry[$i]["img"];?>" width="300" height="160" alt="<?=$apry[$i]["tim"];?>" /></a></div><div class="<?=$cla;?>"><h2><a href="<?=$apry[$i]["lnk"];?>"><?=$apry[$i]["tit"];?></a></h2><span>desde S/. <?=$apry[$i]["pso"];?></span> desde US$ <?=$apry[$i]["pdo"];?></div></li><? }}?></ul><a href="#" id="mycarousel-next"></a>
    <div class="flot"></div>
  </div>
</div>
<div class="izq">
  <div class="itm itn"><div class="icc flef">Inmuebles</div><div class="frig"><a href="<?=URL_PATH;?>inmuebles/" class="vtn">Ver m&aacute;s</a></div><div class="flot"></div><? if($caninm>0){$i=0;foreach($ainm as $inm){$i++;?><div class="him"><?=$inm;?></div><? if($i%3==0||$i==$caninm){echo '<div class="flot"></div>';}}}?></div>
  <div class="itm itp"><div class="icd flef">Constructoras</div><div class="frig"><a href="<?=URL_PATH;?>constructoras/" class="vtn">Ver m&aacute;s</a></div><div class="flot"></div><? if($cancon>0){foreach($acon as $emp){?><div class="hco"><?=$emp;?></div><? }}?><div class="flot"></div></div>
  <div class="itm itp"><div class="icd flef">Agentes</div><div class="frig"><a href="<?=URL_PATH;?>agentes-inmobiliarios/" class="vtn">Ver m&aacute;s</a></div><div class="flot"></div><? if($canage>0){foreach($aage as $emp){?><div class="hco"><?=$emp;?></div><? }}?><div class="flot"></div></div>
</div>
<div class="der">
  <div class="mod"><div id="pasos"></div><script type="text/javascript">swfobject.embedSWF("<?=URL_PATH;?>assets/swf/3pasos.swf","pasos","310","300","9.0.0","<?=URL_PATH;?>assets/js/swfobject/expressInstall.swf",{},{wmode:"transparent"},{});</script></div>
  <div class="mod"><div class="icf">Art&iacute;culos de inter&eacute;s</div><? if($canart > 0){for($i=0;$i<=$canart;$i++){?><div class="mod"><div class="slef"><?=$aart[$i]["img"];?></div><div class="hda"><h3><?=$aart[$i]["tit"];?></h3><?=$aart[$i]["sml"];?></div><div class="flot"></div></div><? }}?></div>
  <!--<div class="mod">
  	[Zona del tabber]
  </div>-->
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>