<?
  include("config.php");
  include(DIR_PATH."assets/includes/class/phpthumb/ThumbLib.inc.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $p = testins($_GET["p"]);
  if($p != ""){
	if(!is_numeric($p)){
	  header("location:".URL_PATH);
	}else{
	  $inm = $db->fetchObject("SELECT tipo,inmuebletipo_id,antiguedad,distrito_id,direccion,nombre FROM inmueble WHERE id=".$p." LIMIT 0,1");
	  if($inm->nombre == ""){
		header("location:".URL_PATH);
	  }else{
		$cat = 1;
		$ope = $inm->tipo; if($ope == 1){$nope = "Compra";}else{$nope = "Alquiler";} $nope = '<div class="txt txg">'.$nope.'</div>';
		$tip = $inm->inmuebletipo_id; $ntip = $db->fetchObject("SELECT nombre FROM inmuebletipo WHERE id=".$tip." LIMIT 0,1"); $ntip = '<div class="txt txg">'.$ntip->nombre.'</div>';
		$ant = $inm->antiguedad; if($ant == 1){$nant = "Proyecto en construcci&oacute;n";}elseif($ant == 2){$nant = "Usado";}elseif($ant == 3){$nant = "Nuevo";}else{$nant = "[No especificado]";}
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
		$str = '<div class="mod"><div class="suv mod">Este inmueble se esta agregando dentro del proyecto:</div><h1><a href="'.URL_PATH.'micuenta-editarpublicacion/?id='.$p.'" class="roj bld">"'.$inm->nombre.'"</a> - <a href="'.URL_PATH.'micuenta-editarpublicacion/?id='.$p.'" class="bld">Volver al proyecto</a></h1></div>';
	  }
	}
  }
  if(isset($_POST["btnok"])){
	//INMUEBLE INDIVIDUAL
	$tip = testins($_POST["cbotip"]); $ope = testins($_POST["cboope"]); $dir = testins($_POST["cbodir"]); $ato = testins($_POST["txtato"]); $aco = testins($_POST["txtaco"]); $pso = testins($_POST["txtpso"]);
	$pdo = testins($_POST["txtpdo"]); $dor = testins($_POST["txtdor"]); $ban = testins($_POST["txtban"]);
	//PROYECTO
	$edi = testins($_POST["txtedi"]); $pis = testins($_POST["txtpis"]); $pag = testins($_POST["txtpag"]);
	//GENERAL
	$cat = testins($_POST["cbocat"]); $nom = testins($_POST["txtnom"]); $url = titurl($nom); $dir = testins($_POST["txtdir"]); $dis = testins($_POST["cbodis"]); $ant = testins($_POST["cboant"]);
	$adi = testins($_POST["txtadi"]); $img = testins($_FILES["filimg"]["name"]); $lat = testins($_POST["hdnlat"]); $lon = testins($_POST["hdnlon"]);
	$arc = $_POST["chkarc"]; if(count($arc) > 0){$arc = implode(",", $arc);}
	if($cat == 1){
	  if($ato == ""){$msg.='<div class="ler">- Area total</div>';}
	}
	if($tip == ""){$msg.='<div class="ler">- Tipo de inmueble</div>';}
	if($nom == ""){$msg.='<div class="ler">- Nombre</div>';}
	if($dir == ""){$msg.='<div class="ler">- Direccion</div>';}
	if($dis == ""){$msg.='<div class="ler">- Distrito</div>';}
	if($img == ""){$msg.='<div class="ler">- Imagen</div>';}
	if($msg != ""){$msg='<div>* Los siguientes campos no deben estar vacios:</div>'.$msg.'<div class="flot"></div>';}
	if($img != ""){
	  $imageinfo = getimagesize($_FILES["filimg"]["tmp_name"]);
	  //if($imageinfo['mime']!='image/gif' && $imageinfo['mime']!='image/jpeg' && $imageinfo['mime']!='image/bmp' && $imageinfo['mime']!='image/wbmp' && $imageinfo['mime']!='image/png'){
	  if($imageinfo['mime']!='image/gif' && $imageinfo['mime']!='image/jpeg' && $imageinfo['mime']!='image/png'){
		$msg.="<div>Tipo de imagen no soportado</div>";
	  }else{
		if($_FILES["archivo"]['size'] >= 1048576){
		  $msg.="<div>Peso de la imagen es mayor a 1MB</div>";
		}
	  }
	}
	if($msg == ""){
	  $idx = $db->fetchObject("SELECT MAX(id)+1 AS idx FROM inmueble LIMIT 0,1"); if($idx->idx == ""){$idx->idx = 1;}
	  $img = $idx->idx.".jpg";
	  $thb = PhpThumbFactory::create($_FILES["filimg"]["tmp_name"]);
	  $thb->adaptiveResize(424, 354);
	  //$thb->resize(176, 62);
	  $imx = $thb->save(DIR_PATH."w/imagenes/inmuebles/".$img);
	  $thb->adaptiveResize(130, 94);
	  $imc = $thb->save(DIR_PATH."w/imagenes/inmuebles/thumbs/".$img);
	  if($imx && $imc){
		if($adi == ""){$adi = "NULL";}else{$adi = "'".$adi."'";}
		if($arc == ""){$arc = "NULL";}else{$arc = "'".$arc."'";}
		if($ant == ""){$ant = "NULL";}else{$ant = "'".$ant."'";}
		if($cat == 1){
		  if($aco == ""){$aco = "NULL";}
		  if($pso == ""){$pso = "NULL";}
		  if($pdo == ""){$pdo = "NULL";}
		  if($dor == ""){$dor = "NULL";}
		  if($ban == ""){$ban = "NULL";}
		  if($p == ""){$pro = "NULL";}else{$pro = $p;}
		  //fecha de 10 dias gratis
		  $year  = date('Y');
		  $month = date('m');
		  $day   = date('d');
		  
		  $fechafin   = mktime(0,0,0,$month,$day+10,$year);
		  $fechafinal = date('Y-m-d' , $fechafin); 
		  
		  $sav = $db->query("INSERT INTO inmueble(id,usuario_id,categoria,proyecto_id,tipo,inmuebletipo_id,nombre,urlnombre,imagen,antiguedad,distrito_id,direccion,areatotalmin,areaconstruidamin,preciomin,preciodolaresmin,dormitoriosmin,banosmin,areacomun,adicionales,latitud,longitud,estado,fechacreacion,fechaactualizacion,fechainicio, fechafin) VALUES(".$idx->idx.",'".$log."','".$cat."',".$pro.",'".$ope."','".$tip."','".$nom."','".$url."','".$img."',".$ant.",".$dis.",'".$dir."',".$ato.",".$aco.",".$pso.",".$pdo.",".$dor.",".$ban.",".$arc.",".$adi.",'".$lat."','".$lon."',1,NOW(),NOW(), NOW(), '".$fechafinal."')");
		  if($sav){
			include(DIR_PATH."assets/includes/xml_inmuebles_home.php");
		  }
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
			  $upd = $db->query("UPDATE inmueble SET areatotalmin=".$aton.",areatotalmax=".$atox.",areaconstruidamin=".$acon.",areaconstruidamax=".$acox.",preciomin=".$pson.",preciomax=".$psox.",preciodolaresmin=".$pdon.",preciodolaresmax=".$pdox.",dormitoriosmin=".$dorn.",dormitoriosmax=".$dorx.",banosmin=".$bann.",banosmax=".$banx.",estado='1' WHERE id=".$p." LIMIT 1");
			}else{
			  $atox=$ato; $aton="NULL"; $atox=$ato; $aton="NULL";
			  $upd = $db->query("UPDATE inmueble SET areatotalmin=".$ato.",areaconstruidamin=".$aco.",preciomin=".$pso.",preciodolaresmin=".$pdo.",dormitoriosmin=".$dor.",banosmin=".$ban.",estado='1' WHERE id=".$p." LIMIT 1");
			}
		  }
	  	}else{
		  //solo inmueble
		  if($edi == ""){$edi = "NULL";}
		  if($pis == ""){$pis = "NULL";}
		  if($pag == ""){$pag = "NULL";}else{$pag = "'".$pag."'";}
		  //fecha de 10 dias gratis
		  $year  = date('Y');
		  $month = date('m');
		  $day   = date('d');
		  
		  $fechafin = mktime(0,0,0,$month,$day+10,$year);
		  $fechafinal = date('Y-m-d' , $fechafin); 
		  
		  $sav = $db->query("INSERT INTO inmueble(id,usuario_id,categoria,tipo,inmuebletipo_id,nombre,urlnombre,imagen,antiguedad,distrito_id,direccion,areacomun,adicionales,nedificios,npisos,web,latitud,longitud,estado,fechacreacion,fechaactualizacion, fechainicio, fechafin) VALUES(".$idx->idx.",'".$log."','".$cat."','".$ope."','".$tip."','".$nom."','".$url."','".$img."',".$ant.",".$dis.",'".$dir."',".$arc.",".$adi.",".$edi.",".$pis.",".$pag.",'".$lat."','".$lon."',0,NOW(),NOW(), NOW(), '".$fechafinal."')");
	  	}
		if($sav){
		  $sav = $db->query("INSERT INTO inmueblegaleria(inmueble_id,imagen,estado,fechacreacion) VALUES(".$idx->idx.",'".$img."','1',NOW())");
		  $sav = $db->query("INSERT INTO inmueblevisita(id,visitas) VALUES(".$idx->idx.",0)");
		}
	  }
	  if($sav){
		header("location:".URL_PATH."micuenta-editarpublicacion/?id=".$idx->idx."&statusimg=ok#inmimg");
	  }else{
		$msg = '<div class="err">Ha ocurrido un error al guardar la informaci&oacute;n.</div>';
	  }
	}else{
	  $msg = '<div class="err">'.$msg.'</div>';
	}
  }
  $dat = file_get_contents(DIR_PATH."assets/js/data/json_dep.json");
  //$dat = json_decode($dat, true);
  
  $page = "micuenta-crearpublicacion";
  $javascript = 'var str=\''.$dat.'\';';
  include(DIR_PATH."assets/includes/header.php");?>
<script type="text/javascript">
  var lat=null;var lng=null;var map=null;var geocoder=null;var marker=null;var c=0;
  $(document).ready(function(){
<?  if($p == ""){?>
	llenarDep(0);
<?	}?>
	$('a[rel="inmmap"]').click(function(){
	  c++;
	  if(c==1){
		initialize();
	  }else{
		codeAddress();
	  }
	});
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
<div class="ubi">Mi cuenta - Crear publicaci&oacute;n</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
<? if($p != ""){echo $str;}?>
  <div class="tbp">
    <ul class="tabs"><li><a href="#inmdes" class="defaulttab" rel="inmdes"><span>Descripcion del inmueble</span></a></li><li><a href="#inmimg" rel="inmimg"><span>Im&aacute;genes</span></a></li><li><a href="#inmmap" rel="inmmap"><span>Ubicaci&oacute;n en el mapa</span></a></li><div class="flot"></div></ul>
<?=$msg;?>
    <form id="frmInm" method="post" action="<?=URI_PATH;?>" enctype="multipart/form-data">
    <div class="tab-content" id="inmdes">
  	  <div class="mod"><div class="lbl">Tipo de registro:</div><div class="flef"><? if($p == ""){?><select id="cbocat" name="cbocat" class="txt txg"><option value="1"<? if($cat=='1'){echo ' selected="selected"';}?>>Inmueble individual</option><? if($ltip==2){?><option value="2"<? if($cat=='2'){echo ' selected="selected"';}?>>Proyecto</option><? }?></select><? }else{?><div class="txt txg">Inmueble individual</div><input type="hidden" name="cbocat" id="cbocat" value="<?=$cat;?>" /><? }?></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Tipo de inmueble:</div><div class="flef"><? if($p==""){?><select id="cbotip" name="cbotip" class="txt txg"><option value="">Seleccione</option><option value="1"<? if($tip=='1'){echo ' selected="selected"';}?>>Casa</option><option value="2"<? if($tip=='2'){echo ' selected="selected"';}?>>Departamento</option><option value="3"<? if($tip=='3'){echo ' selected="selected"';}?>>Habitación</option><option value="4"<? if($tip=='4'){echo ' selected="selected"';}?>>Terreno</option></select><? }else{echo $ntip;?><input type="hidden" name="cbotip" id="cbotip" value="<?=$tip;?>" /><? }?></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Tipo de operaci&oacute;n:</div><div class="flef"><? if($p==""){?><select id="cboope" name="cboope" class="txt txg"><option value="1"<? if($ope=='1'){echo ' selected="selected"';}?>>Compra</option><option value="2"<? if($ope=='2'){echo ' selected="selected"';}?>>Alquiler</option></select><? }else{echo $nope;?><input type="hidden" name="cboope" id="cboope" value="<?=$ope;?>" /><? }?></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Nombre:</div><div class="flef"><input type="text" name="txtnom" id="txtnom" class="txt txg" maxlength="80" value="<?=$nom;?>" /></div><div class="flot"></div></div>
	  <div class="mod"><div class="lbl">Direcci&oacute;n:</div><div class="flef"><? if($p==""){?><input type="text" name="txtdir" id="txtdir" class="txt txg" maxlength="80" value="<?=$dir;?>" /><? }else{echo $ndir;?><input type="hidden" name="txtdir" id="txtdir" value="<?=$dir;?>" /><? }?></div><div class="flot"></div></div>
      <div class="mod"><? if($p==""){?><div class="zlef"><select id="cbodep" class="chi"></select></div><div class="zlef"><select id="cbopro" class="chi"><option value="">[PROVINCIA]</option></select></div><div class="flef"><select name="cbodis" id="cbodis" class="chi"><option value="">[DISTRITO]</option></select></div><div class="flot"></div><? }else{echo $ndis;?><input type="hidden" name="cbodis" id="cbodis" value="<?=$dis;?>" /><? }?></div>
      <div id="dvinm">
<?
  if($cat == 1 || $cat == ""){?>
      <div class="mod"><div class="lbl">Area total:</div><div class="flef"><input type="text" name="txtato" id="txtato" class="txt" maxlength="6" value="<?=$ato;?>" /> m²</div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Area construida:</div><div class="flef"><input type="text" name="txtaco" id="txtaco" class="txt" maxlength="6" value="<?=$aco;?>" /> m²</div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Antiguedad:</div><div class="flef"><? if($p==""){?><select id="cboant" name="cboant" class="txt txg"><option value="">Seleccione</option><option value="1"<? if($ant=='1'){echo ' selected="selected"';}?>>Proyecto en construcci&oacute;n</option><option value="2"<? if($ant=='2'){echo ' selected="selected"';}?>>Usado</option><option value="3"<? if($ant=='3'){echo ' selected="selected"';}?>>Nuevo</option></select><? }else{echo $nant;?><input type="hidden" name="cboant" id="cboant" value="<?=$ant;?>" /><? }?></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Precio:</div><div class="zlef">S/. <input type="text" name="txtpso" id="txtpso" class="txt" maxlength="8" value="<?=$pso;?>" /></div><div class="flef">$. <input type="text" name="txtpdo" id="txtpdo" class="txt" maxlength="8" value="<?=$pdo;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Numero de dormitorios:</div><div class="flef"><input type="text" name="txtdor" id="txtdor" class="txt" maxlength="2" value="<?=$dor;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">Numero de ba&ntilde;os:</div><div class="flef"><input type="text" name="txtban" id="txtban" class="txt" maxlength="2" value="<?=$ban;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">&Aacute;reas comunes:</div><div class="flef"><div class="mod"><input type="checkbox" name="chkarc[]" value="1" /> Canchita de Futbol</div><div class="mod"><input type="checkbox" name="chkarc[]" value="2" /> Patio</div><div class="mod"><input type="checkbox" name="chkarc[]" value="3" /> Lavander&iacute;a</div><div class="mod"><input type="checkbox" name="chkarc[]" value="5" /> Piscina</div><div class="mod"><input type="checkbox" name="chkarc[]" value="6" /> Gimnasio</div><div><input type="checkbox" name="chkarc[]" value="7" /> Juegos para ni&ntilde;os</div></div>
	  <div class="flot"></div>
	  </div>
	  <div>
	  <div class="lbl">Datos adicionales Inmuebles:</div>
	  <div class="flef"><textarea name="txtadi" class="tex txg"><?=testsho($adi);?></textarea></div>
	  <div class="flot"></div>
	  <div class="lbl">Dias de Publicaci&oacute;n:</div>
	  <div class="flef"><strong>10 dias</strong></div>
	  </div>
<?
  }else{?>
      <div class="mod"><div class="lbl">Antiguedad:</div><div class="flef"><select id="cboant" name="cboant" class="txt txg"><option value="">Seleccione</option><option value="1"<? if($nant=='1'){echo ' selected="selected"';}?>>Proyecto en construcci&oacute;n</option><option value="2"<? if($ant=='2'){echo ' selected="selected"';}?>>Usado</option><option value="3"<? if($nant=='3'){echo ' selected="selected"';}?>>Nuevo</option></select></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">&Aacute;reas comunes:</div><div class="flef"><div class="mod"><input type="checkbox" name="chkarc[]" value="1" /> Canchita de Futbol</div><div class="mod"><input type="checkbox" name="chkarc[]" value="2" /> Patio</div><div class="mod"><input type="checkbox" name="chkarc[]" value="3" /> Lavander&iacute;a</div><div class="mod"><input type="checkbox" name="chkarc[]" value="5" /> Piscina</div><div class="mod"><input type="checkbox" name="chkarc[]" value="6" /> Gimnasio</div><div><input type="checkbox" name="chkarc[]" value="7" /> Juegos para ni&ntilde;os</div></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">N° edificios:</div><div class="zlef"><input type="text" name="txtedi" id="txtedi" class="txt" maxlength="2" value="<?=$edi;?>" /></div><div class="lbl">N° pisos:</div><div class="flef"><input type="text" name="txtpis" id="txtpis" class="txt" maxlength="2" value="<?=$pis;?>" /></div><div class="flot"></div></div>
      <div class="mod"><div class="lbl">N° pisos:</div><div class="flef"><input type="text" name="txtpis" id="txtpis" class="txt" maxlength="2" value="<?=$pis;?>" /></div><div class="flot"></div></div>
      <div><div class="lbl">P&aacute;gina del proyecto:</div><div class="flef"><input type="text" name="txtpag" id="txtpag" class="txt txg" maxlength="80" value="<?=$pag;?>" /></div><div class="flot"></div></div>
      <div class="itm itp">
	  <div class="lbl">Datos adicionales P:</div>
	  <div class="flef"><textarea name="txtadi" class="tex txg"><?=$adi;?></textarea></div>
	  <div class="flot"></div>
	  
	  <div class="flot"></div>
	  <div class="lbl">Dias de Publicaci&oacute;n:</div>
	  <div class="flef"><strong>10 dias</strong></div>
	  
	  </div>
	  
      <div><div class="ver mod">AGREGAR INMUEBLES DEL PROYECTO</div><div class="fcen roj">[Primero debe guardar el proyecto para agregar inmuebles]</div></div>
<?
  }?>
      </div>
  	</div>
    <div class="tab-content" id="inmimg">
      <div class="mod">Selecciona una imagen</div>
      <div class="mad"><input type="file" name="filimg" id="filimg" class="txt txg" /></div>
      <div class="itm itp"><span class="ver">Debes al menos ingresar una imagen.</span> Peso m&aacute;ximo: 1MB en formato jpg, gif o png</div>
      <div class="slef">Elige una imagen como principal haciendo clic en <span class="bld">Foto principal</span> o elim&iacute;nala con </div><div class="del flef"></div><div class="flot"></div>
      <div class="its fcen"><span class="ver">[Para ingresar mas im&aacute;genes, primero debe guardar el inmueble.]</span></div>
  	</div>
    <div class="tab-content" id="inmmap">
      <div id="map_canvas" style="width:400px;height:400px;margin:0px auto;"></div>
      <input type="hidden" id="hdnlat" name="hdnlat" value="" /><input type="hidden" id="hdnlon" name="hdnlon" value="" />
  	</div>
    <div class="btn btx"><span><input type="submit" name="btnok" value="Guardar" /></span></div>
    <div class="lod"><img src="<?=URL_PATH;?>assets/img/loading.gif" align="absmiddle" /><span class="ver">Procesando...</span></div>
    </form>
  </div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>