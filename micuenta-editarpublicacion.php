<?
  include("config.php");
  include(DIR_PATH."assets/includes/class/phpthumb/ThumbLib.inc.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $id = testins($_GET["id"]); if(!is_numeric($id)){header("location:".URL_PATH);}
  
  if(isset($_POST["btnok"])){
	//INMUEBLE INDIVIDUAL
	$tip = testins($_POST["cbotip"]); $ope = testins($_POST["cboope"]); $dir = testins($_POST["cbodir"]); $ato = testins($_POST["txtato"]); $aco = testins($_POST["txtaco"]); $pso = testins($_POST["txtpso"]);
	$pdo = testins($_POST["txtpdo"]); $dor = testins($_POST["txtdor"]); $ban = testins($_POST["txtban"]);
	//PROYECTO
	$edi = testins($_POST["txtedi"]); $pis = testins($_POST["txtpis"]); $pag = testins($_POST["txtpag"]);
	//GENERAL
	$cat = testins($_POST["cbocat"]); $nom = testins($_POST["txtnom"]); $url = titurl($nom); $dir = testins($_POST["txtdir"]); $dis = testins($_POST["cbodis"]); $ant = testins($_POST["cboant"]);
	$adi = testins($_POST["txtadi"]); $lat = testins($_POST["hdnlat"]); $lon = testins($_POST["hdnlon"]);
	$arc = $_POST["chkarc"]; if(count($arc) > 0){$arc = implode(",", $arc);}
	if($cat == 1){
	  if($tip == ""){$msg.="- Tipo de inmueble";}
	  if($ato == ""){$msg.="- Area total";}
	}
	if($nom == ""){$msg.="- Nombre";}
	if($dir == ""){$msg.="- Direccion";}
	if($dis == ""){$msg.="- Distrito";}
	if($msg != ""){$msg='<div>* Los siguientes campos no deben estar vacios:</div>'.$msg.'<div class="flot"></div>';}
	if($msg == ""){
	  if($adi == ""){$adi = "NULL";}else{$adi = "'".$adi."'";}
	  if($arc == ""){$arc = "NULL";}else{$arc = "'".$arc."'";}
	  if($ant == ""){$ant = "NULL";}else{$ant = "'".$ant."'";}
	  if($cat == 1){
		if($aco == ""){$aco = "NULL";}
		if($pso == ""){$pso = "NULL";}
		if($pdo == ""){$pdo = "NULL";}
		if($dor == ""){$dor = "NULL";}
		if($ban == ""){$ban = "NULL";}
		$sav = $db->query("UPDATE inmueble SET tipo='".$ope."',inmuebletipo_id='".$tip."',nombre='".$nom."',urlnombre='".$url."',antiguedad=".$ant.",distrito_id=".$dis.",direccion='".$dir."',areatotalmin=".$ato.",areaconstruidamin=".$aco.",preciomin=".$pso.",preciodolaresmin=".$pdo.",dormitoriosmin=".$dor.",banosmin=".$ban.",areacomun=".$arc.",adicionales=".$adi.",latitud='".$lat."',longitud='".$lon."' WHERE id=".$id." LIMIT 1");
		if($sav){
		  include(DIR_PATH."assets/includes/xml_inmuebles_home.php");
		}
		$p = $db->fetchObject("SELECT proyecto_id FROM inmueble WHERE id=".$id."LIMIT 0,1"); $p = $p->proyecto_id;
		if($p != "" && $sav){//Si el inmueble se esta guardando en algun proyecto
		  $inms = $db->query("SELECT areatotalmin,areaconstruidamin,preciomin,preciodolaresmin,dormitoriosmin,banosmin FROM inmueble WHERE proyecto_id=".$p);
		  if(mysql_num_rows($inms)){
			while($inm = mysql_fetch_object($inms)){
			  $aato[] = $inm->areatotalmin; $aaco[] = $inm->areaconstruidamin; $apso[] = $inm->preciomin; $apdo[] = $inm->preciodolaresmin; $ador[] = $inm->dormitoriosmin;
			  $aban[] = $inm->banosmin;
			}
			$atox=max($aato); $aton=min($aato); if($atox==""||$aton==""){if($atox==""){$atox="NULL";};if($aton==""){$aton="NULL";}}else{if($atox==$aton){$atox="NULL";}}
			$acox=max($aaco); $acon=min($aaco); if($acox==""||$acon==""){if($acox==""){$acox="NULL";};if($acon==""){$acon="NULL";}}else{if($acox==$acon){$acox="NULL";}}
			$psox=max($apso); $pson=min($apso); if($psox==""||$pson==""){if($psox==""){$psox="NULL";};if($pson==""){$pson="NULL";}}else{if($psox==$pson){$psox="NULL";}}
			$pdox=max($apdo); $pdon=min($apdo); if($pdox==""||$pdon==""){if($pdox==""){$pdox="NULL";};if($pdon==""){$pdon="NULL";}}else{if($pdox==$pdon){$pdox="NULL";}}
			$dorx=max($ador); $dorn=min($ador); if($dorx==""||$dorn==""){if($dorx==""){$dorx="NULL";};if($dorn==""){$dorn="NULL";}}else{if($dorx==$dorn){$dorx="NULL";}}
			$banx=max($aban); $bann=min($aban); if($banx==""||$bann==""){if($banx==""){$banx="NULL";};if($bann==""){$bann="NULL";}}else{if($banx==$bann){$banx="NULL";}}			
			$upd = $db->query("UPDATE inmueble SET areatotalmin=".$aton.",areatotalmax=".$atox.",areaconstruidamin=".$acon.",areaconstruidamax=".$acox.",preciomin=".$pson.",preciomax=".$psox.",preciodolaresmin=".$pdon.",preciodolaresmax=".$pdox.",dormitoriosmin=".$dorn.",dormitoriosmax=".$dorx.",banosmin=".$bann.",banosmax=".$banx." WHERE id=".$p." LIMIT 1");
		  }
		}
	  }else{
		if($edi == ""){$edi = "NULL";}
		if($pis == ""){$pis = "NULL";}
		if($pag == ""){$pag = "NULL";}else{$pag = "'".$pag."'";}
		$sav = $db->query("UPDATE inmueble SET tipo='".$ope."',inmuebletipo_id='".$tip."',nombre='".$nom."',urlnombre='".$url."',antiguedad=".$ant.",distrito_id=".$dis.",direccion='".$dir."',areacomun=".$arc.",adicionales=".$adi.",adicionales=".$adi.",nedificios=".$edi.",npisos=".$pis.",web=".$pag.",latitud='".$lat."',longitud='".$lon."' WHERE id=".$id." LIMIT 1");
		$inms = $db->query("SELECT id FROM inmueble WHERE proyecto_id=".$id);//si el proyecto tiene inmuebles asociados,estos se actualizan
		if(mysql_num_rows($inms) > 0){
		  $upd = $db->query("UPDATE inmueble SET tipo='".$ope."',inmuebletipo_id='".$tip."',antiguedad=".$ant.",distrito_id=".$dis.",direccion='".$dir."' WHERE proyecto_id=".$id);
		}
	  }
	  if($sav){
		header("location:".URL_PATH."micuenta-editarpublicacion/?id=".$id."&status=ok");
	  }else{
		$msg = '<div class="err">Ha ocurrido un error al guardar la informaci&oacute;n.</div>';
	  }
	}else{
	  $msg = '<div class="err">'.$msg.'</div>';
	}
  }
  if(isset($_POST["btnokimg"])){
	$img = $_FILES["filimg"]["name"];
	if($img != ""){
	  $imageinfo = getimagesize($_FILES["filimg"]["tmp_name"]);
	  //if($imageinfo['mime']!='image/gif' && $imageinfo['mime']!='image/jpeg' && $imageinfo['mime']!='image/bmp' && $imageinfo['mime']!='image/wbmp' && $imageinfo['mime']!='image/png'){
	  if($imageinfo['mime']!='image/gif' && $imageinfo['mime']!='image/jpeg' && $imageinfo['mime']!='image/png'){
	  	$msg = '<div class="err itp">Tipo de imagen no soportado</div>';
	  }else{
	  	if($_FILES["archivo"]['size'] >= 1048576){
		  $msg = '<div class="err itp">Peso de la imagen es mayor a 1MB</div>';
	  	}else{
		  $img = $id."-".md5(time()).".jpg";
	  	  $thb = PhpThumbFactory::create($_FILES["filimg"]["tmp_name"]);
	  	  $thb->adaptiveResize(424, 354);
	  	  //$thb->resize(176, 62);
	  	  $imx = $thb->save(DIR_PATH."w/imagenes/inmuebles/".$img);
	  	  $thb->adaptiveResize(130, 94);
	  	  $imc = $thb->save(DIR_PATH."w/imagenes/inmuebles/thumbs/".$img);
		  if($imx && $imc){
			$sav = $db->query("INSERT INTO inmueblegaleria(inmueble_id,imagen,estado,fechacreacion) VALUES(".$id.",'".$img."','1',NOW())");
			if($sav){
			  header("location:".URL_PATH."micuenta-editarpublicacion/?id=".$id."#inmimg");
			}else{
			  $msg = '<div class="err itp">Ha ocurrido un error en el guardado de las im&aacute;genes</div>';
			}
		  }else{
			$msg = '<div class="err itp">Ha ocurrido un error en el guardado de las im&aacute;genes</div>';
		  }
	  	}
	  }
	}
  }
  
  $dat = $db->fetchObject("SELECT id,categoria,proyecto_id,tipo,inmuebletipo_id,nombre,imagen,antiguedad,distrito_id,direccion,areatotalmin,areaconstruidamin,areaconstruidamax,preciomin,preciomax,preciodolaresmin,preciodolaresmax,dormitoriosmin,banosmin,areacomun,adicionales,nedificios,npisos,web,latitud,longitud FROM inmueble WHERE id=".$id." AND usuario_id='".$log."' LIMIT 0,1");
  $p = $dat->proyecto_id;
  if($dat->id != ""){
  	$cat = $dat->categoria; $ope = $dat->tipo; $tip = $dat->inmuebletipo_id; $nom = testsho($dat->nombre); $dir = testsho($dat->direccion); $dis = $dat->distrito_id;
	$img = $dat->imagen; $adi = testsho($dat->adicionales); $ant = $dat->antiguedad; $arc = $dat->areacomun; $arc = explode(",", $arc); $lat = $dat->latitud; $lon = $dat->longitud;
  	if($lat=="" || $lon==""){$lat = "-12.047552423416382"; $lon = "-77.06209659576416";}
  	if($cat == 1){
	  $ato = $dat->areatotalmin; $aco = $dat->areaconstruidamin; $pso = $dat->preciomin; $pdo = $dat->preciodolaresmin; $dor = $dat->dormitoriosmin; $ban = $dat->banosmin;
	  if($p != ""){
		$inm = $db->fetchObject("SELECT tipo,inmuebletipo_id,antiguedad,distrito_id,direccion,nombre FROM inmueble WHERE id=".$p." LIMIT 0,1");
	  	if($inm->nombre == ""){
		  header("location:".URL_PATH);
	  	}else{
		  $ope = $inm->tipo; if($ope == 1){$nope = "Compra";}else{$nope = "Alquiler";} $nope = '<div class="txt txg">'.$nope.'</div>';
		  $tip = $inm->inmuebletipo_id; $ntip = $db->fetchObject("SELECT nombre FROM inmuebletipo WHERE id=".$tip." LIMIT 0,1"); $ntip = '<div class="txt txg">'.$ntip->nombre.'</div>';
		  $ant = $inm->antiguedad; if($ant == 1){$nant="Proyecto en construcci&oacute;n";}elseif($ant == 2){$nant="Usado";}elseif($ant == 3){$nant="Nuevo";}else{$nant="[No especificado]";}
		  $nant = '<div class="txt txg">'.$nant.'</div>';
		  $dis = $inm->distrito_id;
		  if($dis != ""){
	  	  	$ndis = $db->fetchObject("SELECT nombre,provincia_id FROM distrito WHERE id=".$dis." LIMIT 0,1");
	  	  	$npro = $db->fetchObject("SELECT nombre,departamento_id FROM provincia WHERE id=".$ndis->provincia_id." LIMIT 0,1");
		  	$ndep = $db->fetchObject("SELECT nombre FROM departamento WHERE id=".$npro->departamento_id." LIMIT 0,1");
		  	$ndis = '<div class="txt txg">'.$ndep->nombre." - ".$npro->nombre." - ".$ndis->nombre.'</div>';
		  }else{
	  	  	$ndis = '<div class="txt txg">[No especificado]</div>';
		  }
		  $dir = testsho($inm->direccion); $ndir = '<div class="txt txg">'.$dir.'</div>';
		  $str = '<div class="mod"><div class="suv mod">Este inmueble pertenece al proyecto:</div><h1><a href="'.URL_PATH.'micuenta-editarpublicacion/?id='.$p.'" class="roj bld">"'.$inm->nombre.'"</a> - <a href="'.URL_PATH.'micuenta-editarpublicacion/?id='.$p.'" class="bld">Volver al proyecto</a></h1></div>';
	  	}
	  }
    }else{
	  $edi = testsho($dat->nedificios); $pis = testsho($dat->npisos); $pag = testsho($dat->web);
	  $inms = $db->query("SELECT id,nombre,urlnombre,imagen,fechacreacion FROM inmueble WHERE proyecto_id=".$id);
	  while($inm = mysql_fetch_object($inms)){
		$lnk = URL_PATH."inmueble-detalle/".$inm->id."/".$inm->urlnombre;
		$ainm[] = '<div class="slef"><a href="'.$lnk.'" target="_blank"><img src="'.URW_PATH.'imagenes/inmuebles/thumbs/'.$inm->imagen.'" width="70" /></a></div><div class="det"><div class="chi">'.fecha($inm->fechacreacion,true).'</div><div class="tit"><h3><a href="'.$lnk.'" target="_blank">'.testsho($inm->nombre).'</a></h3></div></div><div class="dpr"><div class="btn btx"><span><a href="'.URL_PATH.'micuenta-editarpublicacion/?id='.$inm->id.'">Editar</a></span></div></div><div class="flot"></div>';
	  }
	  $caninm = count($ainm);
  	}
	if($dis != ""){
	  $pro = $db->fetchObject("SELECT provincia_id FROM distrito WHERE id=".$dat->distrito_id." LIMIT 0,1"); $pro = $pro->provincia_id;
	  $dep = $db->fetchObject("SELECT departamento_id FROM provincia WHERE id=".$pro." LIMIT 0,1"); $dep = $dep->departamento_id;
	}else{
	  $dep = 0; $pro = 0; $dis = 0;
	}
	$gals = $db->query("SELECT id,imagen FROM inmueblegaleria WHERE inmueble_id=".$id." ORDER BY id");
	while($gal = mysql_fetch_object($gals)){
	  if($img == $gal->imagen){
		$strimg = '<img src="'.URW_PATH.'imagenes/inmuebles/thumbs/'.$gal->imagen.'" width="130" height="94" /><span class="roj">Foto principal</span>';
	  }else{
		$strimg = '<a href="'.URL_PATH.'micuenta-delimagen/?id='.$gal->id.'&inm='.$id.'" class="del"></a><img src="'.URW_PATH.'imagenes/inmuebles/thumbs/'.$gal->imagen.'" width="130" height="94" /><a href="'.URL_PATH.'micuenta-priimagen/?id='.$gal->id.'&inm='.$id.'">Foto principal</a>';
	  }
	  $agal[] = $strimg;
	}
	$cangal = count($agal);
  }else{
	header("location:".URL_PATH);
  }
  
  $dat = file_get_contents(DIR_PATH."assets/js/data/json_dep.json");
  
  $page = "micuenta-editarpublicacion";
  $javascript = 'var str=\''.$dat.'\';';
  include(DIR_PATH."assets/includes/header.php");?>
<?
  if(($p==""&&$cat==1)||$cat==2){?>
<script type="text/javascript">
  var lat=null;var lng=null;var map=null;var geocoder=null;var marker=null;var c=0;
  $(document).ready(function(){
	$('a[rel="inmmap"]').click(function(){
	  c++;
	  if(c==1){
		initialize();
	  }else{
		codeAddress();
	  }
	});
	llenarDep(<?=$dep;?>); llenarPro(<?=$dep;?>,<?=$pro;?>); llenarDis(<?=$pro;?>,<?=$dis;?>);
  });
  function initialize(){
	lat=$("#hdnlat").val();lng=$("#hdnlon").val();
	geocoder=new google.maps.Geocoder();
	if(lat!=''&&lng!=''){var latLng=new google.maps.LatLng(lat,lng);}else{var latLng=new google.maps.LatLng(-12.0881394,-77.02748539999999);}
	var myOptions={center:latLng,zoom:15,mapTypeId:google.maps.MapTypeId.ROADMAP};
	map=new google.maps.Map(document.getElementById("map_canvas"),myOptions);
	marker=new google.maps.Marker({map:map,position:latLng,draggable:true});
	updatePosition(latLng);
  }
  function codeAddress(){
	var address="";
	var dir = $("#txtdir").val();if(dir!=""){address+=dir+" ";}
	var dep=$("#cbodep").val();var pro=$("#cbopro").val();var dis=$("#cbodis").val();
	if(dep!=""){address+=$("#cbodep option:selected").html()+" ";}
	if(pro!=""){address+=$("#cbopro option:selected").html()+" ";}
	if(dis!=""){address+=$("#cbodis option:selected").html()+" ";}
	if(address == "") address = "Lima Peru";
	geocoder.geocode(
	  {'address':address},
	  function(results,status){
		if(status==google.maps.GeocoderStatus.OK){
		  map.setCenter(results[0].geometry.location);
		  marker.setPosition(results[0].geometry.location);
		  updatePosition(results[0].geometry.location);
		  google.maps.event.addListener(marker,'dragend',function(){
			updatePosition(marker.getPosition());
		  });
		}else{
		  alert("No podemos encontrar la direcci&oacute;n, error: "+ status);
		}
	  }
	);
  }
  function updatePosition(latLng){$('#hdnlat').val(latLng.lat());$('#hdnlon').val(latLng.lng());}
</script>
<?
  }?>
<div class="ubi">Mi cuenta - Editar publicaci&oacute;n</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
<? if($p != ""){echo $str;}?>
  <div class="tbp">
    <ul class="tabs"><li><a href="#inmdes" class="defaulttab" rel="inmdes"><span>Descripcion del inmueble</span></a></li><li><a href="#inmimg" rel="inmimg"><span>Im&aacute;genes</span></a></li><? if(($p==""&&$cat==1)||$cat==2){?><li><a href="#inmmap" rel="inmmap"><span>Ubicaci&oacute;n en el mapa</span></a></li><? }?><div class="flot"></div></ul>
    <div class="tab-content" id="inmdes">
      <? if($_GET["status"] == "ok"){?><div class="god itp">Datos guardados correctamente</div><? }?>
      <?=$msg;?>
      <form id="frmInm" method="post" action="<?=URI_PATH;?>" enctype="multipart/form-data">
  	  <div class="mod"><div class="lbl">Tipo de registro:</div><div class="flef"><div class="txt txg"><? if($cat==1){echo "Inmueble individual";}else{echo "Proyecto";}?></div><input type="hidden" name="cbocat" id="cbocat" value="<?=$cat;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Tipo de inmueble:</div><div class="flef"><? if($p==""){?><select id="cbotip" name="cbotip" class="txt txg"><option value="">Seleccione</option><option value="1"<? if($tip=='1'){echo ' selected="selected"';}?>>Casa</option><option value="2"<? if($tip=='2'){echo ' selected="selected"';}?>>Departamento</option><option value="3"<? if($tip=='3'){echo ' selected="selected"';}?>>Habitación</option><option value="4"<? if($tip=='4'){echo ' selected="selected"';}?>>Terreno</option></select><? }else{echo $ntip;}?><input type="hidden" name="cbotip" id="cbotip" value="<?=$tip;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Tipo de operaci&oacute;n:</div><div class="flef"><? if($p==""){?><select id="cboope" name="cboope" class="txt txg"><option value="1"<? if($ope=='1'){echo ' selected="selected"';}?>>Compra</option><option value="2"<? if($ope=='2'){echo ' selected="selected"';}?>>Alquiler</option></select><? }else{echo $nope;}?><input type="hidden" name="cboope" id="cboope" value="<?=$ope;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Nombre:</div><div class="flef"><input type="text" name="txtnom" id="txtnom" class="txt txg" maxlength="80" value="<?=$nom;?>" /></div><div class="flot"></div></div>
	  <div class="mod"><div class="lbl">Direcci&oacute;n:</div><div class="flef"><? if($p==""){?><input type="text" name="txtdir" id="txtdir" class="txt txg" maxlength="80" value="<?=$dir;?>" /><? }else{echo $ndir;?><input type="hidden" name="txtdir" id="txtdir" value="<?=$dir;?>" /><? }?></div><div class="flot"></div></div>
      <div class="mod"><? if($p==""){?><div class="zlef"><select id="cbodep" class="chi"></select></div><div class="zlef"><select id="cbopro" class="chi"><option value="">[PROVINCIA]</option></select></div><div class="flef"><select name="cbodis" id="cbodis" class="chi"><option value="">[DISTRITO]</option></select></div><div class="flot"></div><? }else{echo $ndis;?><input type="hidden" name="cbodis" id="cbodis" value="<?=$dis;?>" /><? }?></div>
<?
  if($cat == 1){?>
      <div class="mod"><div class="lbl">Area total:</div><div class="flef"><input type="text" name="txtato" id="txtato" class="txt" maxlength="6" value="<?=$ato;?>" /> m²</div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Area construida:</div><div class="flef"><input type="text" name="txtaco" id="txtaco" class="txt" maxlength="6" value="<?=$aco;?>" /> m²</div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Antiguedad:</div><div class="flef"><? if($p==""){?><select id="cboant" name="cboant" class="txt txg"><option>Seleccione</option><option value="1"<? if($ant=='1'){echo ' selected="selected"';}?>>Proyecto en construcci&oacute;n</option><option value="2"<? if($ant=='2'){echo ' selected="selected"';}?>>Usado</option><option value="3"<? if($ant=='3'){echo ' selected="selected"';}?>>Nuevo</option></select><? }else{echo $nant;?><input type="hidden" name="cboant" id="cboant" value="<?=$ant;?>" /><? }?></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Precio:</div><div class="zlef">S/. <input type="text" name="txtpso" id="txtpso" class="txt" maxlength="8" value="<?=$pso;?>" /></div><div class="flef">$. <input type="text" name="txtpdo" id="txtpdo" class="txt" maxlength="8" value="<?=$pdo;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Numero de dormitorios:</div><div class="flef"><input type="text" name="txtdor" id="txtdor" class="txt" maxlength="2" value="<?=$dor;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Numero de ba&ntilde;os:</div><div class="flef"><input type="text" name="txtban" id="txtban" class="txt" maxlength="2" value="<?=$ban;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">&Aacute;reas comunes:</div><div class="flef"><div class="mod"><input type="checkbox" name="chkarc[]" value="1"<? if(in_array(1,$arc)){echo ' checked="checked"';}?> /> Canchita de Futbol</div><div class="mod"><input type="checkbox" name="chkarc[]" value="2"<? if(in_array(2,$arc)){echo ' checked="checked"';}?> /> Patio</div><div class="mod"><input type="checkbox" name="chkarc[]" value="3"<? if(in_array(3,$arc)){echo ' checked="checked"';}?> /> Lavander&iacute;a</div><div class="mod"><input type="checkbox" name="chkarc[]" value="3"<? if(in_array(4,$arc)){echo ' checked="checked"';}?> /> Estacionamiento</div><div class="mod"><input type="checkbox" name="chkarc[]" value="5"<? if(in_array(5,$arc)){echo ' checked="checked"';}?> /> Piscina</div><div class="mod"><input type="checkbox" name="chkarc[]" value="6"<? if(in_array(6,$arc)){echo ' checked="checked"';}?> /> Gimnasio</div><div><input type="checkbox" name="chkarc[]" value="7"<? if(in_array(7,$arc)){echo ' checked="checked"';}?> /> Juegos para ni&ntilde;os</div></div><div class="flot"></div></div><div><div class="lbl">Datos adicionales:</div><div class="flef"><textarea name="txtadi" class="tex txg"><?=$adi;?></textarea></div><div class="flot"></div></div>
      <div class="btn btx"><span><input type="submit" name="btnok" value="Guardar" /></span></div>
      <div class="lod"><img src="<?=URL_PATH;?>assets/img/loading.gif" align="absmiddle" /><span class="ver">Procesando...</span></div>
<?
  }else{?>
	  <div class="mod"><div class="lbl">Antiguedad:</div><div class="flef"><select id="cboant" name="cboant" class="txt txg"><option value="">Seleccione</option><option value="1"<? if($nant=='1'){echo ' selected="selected"';}?>>Proyecto en construcci&oacute;n</option><option value="2"<? if($ant=='2'){echo ' selected="selected"';}?>>Usado</option><option value="3"<? if($nant=='3'){echo ' selected="selected"';}?>>Nuevo</option></select></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">&Aacute;reas comunes:</div><div class="flef"><div class="mod"><input type="checkbox" name="chkarc[]" value="1"<? if(in_array(1,$arc)){echo ' checked="checked"';}?> /> Canchita de Futbol</div><div class="mod"><input type="checkbox" name="chkarc[]" value="2"<? if(in_array(2,$arc)){echo ' checked="checked"';}?> /> Patio</div><div class="mod"><input type="checkbox" name="chkarc[]" value="3"<? if(in_array(3,$arc)){echo ' checked="checked"';}?> /> Lavander&iacute;a</div><div class="mod"><input type="checkbox" name="chkarc[]" value="3"<? if(in_array(4,$arc)){echo ' checked="checked"';}?> /> Estacionamiento</div><div class="mod"><input type="checkbox" name="chkarc[]" value="5"<? if(in_array(5,$arc)){echo ' checked="checked"';}?> /> Piscina</div><div class="mod"><input type="checkbox" name="chkarc[]" value="6"<? if(in_array(6,$arc)){echo ' checked="checked"';}?> /> Gimnasio</div><div><input type="checkbox" name="chkarc[]" value="7"<? if(in_array(7,$arc)){echo ' checked="checked"';}?> /> Juegos para ni&ntilde;os</div></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">N° edificios:</div><div class="flef"><input type="text" name="txtedi" id="txtedi" class="txt" maxlength="2" value="<?=$edi;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">N° pisos:</div><div class="flef"><input type="text" name="txtpis" id="txtpis" class="txt" maxlength="2" value="<?=$pis;?>" /></div><div class="flot"></div></div>
      <div><div class="lbl">P&aacute;gina del proyecto:</div><div class="flef"><input type="text" name="txtpag" id="txtpag" class="txt txg" maxlength="80" value="<?=$pag;?>" /></div><div class="flot"></div></div>
      <div class="itm itp"><div class="lbl">Datos adicionales:</div><div class="flef"><textarea name="txtadi" class="tex txg"><?=$adi;?></textarea></div><div class="flot"></div></div>
      <div class="btn btx"><span><input type="submit" name="btnok" value="Guardar" /></span></div>
      <div class="lod"><img src="<?=URL_PATH;?>assets/img/loading.gif" align="absmiddle" /><span class="ver">Procesando...</span></div>
      <div class="its">
      	<div class="ver mod"><a href="<?=URL_PATH;?>micuenta-crearpublicacion/?p=<?=$id;?>">AGREGAR INMUEBLES A ESTE PROYECTO</a></div>
        <div class="frc frn frb">
<? if($caninm > 0){foreach($ainm as $inm){?><div class="itm"><?=$inm;?></div><? }}else{?><div class="fcen roj">[Debe agregar un inmueble a este proyecto para que sea visible en la p&aacute;gina]</div><? }?>
        </div>
      </div>
<?
  }?><input type="hidden" id="hdnlat" name="hdnlat" value="<?=$lat;?>" /><input type="hidden" id="hdnlon" name="hdnlon" value="<?=$lon;?>" />
      </form>
  	</div>
    <div class="tab-content" id="inmimg">
	  <? if($_GET["statusimg"] == "ok"){?><div class="god itp">Datos guardados correctamente. Ahora puede agregar mas imagenes</div><? }?>
      <form id="frmInmImg" method="post" action="" enctype="multipart/form-data">
      <? if($_GET["status"] == "err"){echo '<div class="err itp">No se puede borrar la unica imagen del inmueble</div>';}?>
      <div class="mod">Selecciona una imagen</div>
      <div class="mad"><div class="slef"><input type="file" name="filimg" id="filimg" class="txt" /></div><div class="btn btm flef"><span><input type="submit" name="btnokimg" value="Guardar imagen" /></span></div><div class="lod flef"><img src="<?=URL_PATH;?>assets/img/loading.gif" align="absmiddle" /><span class="ver">Procesando...</span></div><div class="flot"></div></div>
      <div class="itm itp"><span class="ver">Debes al menos ingresar una imagen.</span> Peso m&aacute;ximo: 1MB en formato jpg, gif o png</div>
      <div class="slef">Elige una imagen como principal haciendo clic en <span class="bld">Foto principal</span> o elim&iacute;nala con </div><div class="del flef"></div><div class="flot"></div>
      <div class="its"><? if($cangal > 0){foreach($agal as $gal){echo '<div class="gap">'.$gal.'</div>';}}?><div class="flot"></div></div>
      </form>
  	</div>
<?
  if(($p==""&&$cat==1)||$cat==2){?>
    <div class="tab-content" id="inmmap">
      <div id="map_canvas" style="width:400px;height:400px;margin:0px auto;"></div>
  	</div>
<?
  }?>
    <input type="hidden" id="hdnfrmtip" value="upd" />
  </div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>