<?
  include("config.php");
  include(DIR_PATH."assets/includes/class/phpthumb/ThumbLib.inc.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  if(isset($_POST["btnok"])){
	$reg = testins($_POST["hdnreg"]);
	//PERSONA NATURAL
	$nom = testins($_POST["txtnom"]); $ape = testins($_POST["txtape"]); $dni = testins($_POST["txtdni"]);
	//EMPRESA
	$ruc = testins($_POST["txtruc"]); $com = testins($_POST["txtcom"]); $con = testins($_POST["txtcon"]); $img = testins($_FILES["filimg"]["name"]);
	$te1 = testins($_POST["txtte1"]); $te2 = testins($_POST["txtte2"]); $te3 = testins($_POST["txtte3"]); $dir = testins($_POST["txtdir"]);
	$pag = testins($_POST["txtpag"]); $des = testins($_POST["txtdes"]);
	//GENERAL
	$ima = testins($_POST["hdnima"]);
	$bol = testins($_POST["chkbol"]); if($bol == ""){$bol = 0;}
	
	if($reg == 1){
	  if($nom == ""){$msg.="- Nombres";}
	  if($ape == ""){$msg.="- Apellidos";}
	  if($dni == ""){$msg.="- DNI";}
	}else{
	  if($com == ""){$msg.='<div class="ler">- Nombre comercial</div>';}
	  if($ruc == ""){$msg.='<div class="ler">- RUC</div>';}
	  if($con == ""){$msg.='<div class="ler">- Nombre de contacto</div>';}
	  if($dir == ""){$msg.='<div class="ler">- Direccion</div>';}
	  if($ima == ""){$msg.='<div class="ler">- Logo</div>';}
	  if($des == ""){$msg.='<div class="ler">- Descripcion</div>';}
	}
	if($te1 == ""){$msg.='<div class="ler">- Telefono 1</div>';}
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
	  if($img != ""){
		$img = $log.".jpg";
		$thb = PhpThumbFactory::create($_FILES["filimg"]["tmp_name"]);
		if($reg == 1){$thb->adaptiveResize(76, 76);}else{$thb->adaptiveResize(176, 62);}
		$thb->save(DIR_PATH."w/imagenes/usuarios/".$img);
		$img = "'".$img."'";
	  }else{
		if($ima == ""){
		  $img = "NULL";
		}else{
		  $img = "'".$log.".jpg'";
		}
	  }
	  if($te2 == ""){$te2 = "NULL";}else{$te2 = "'".$te2."'";} if($te3 == ""){$te3 = "NULL";}else{$te3 = "'".$te3."'";}
	  if($bol == ""){$bol = 0;}
	  $sav = $db->query("UPDATE usuario SET imagen=".$img.",telefono1='".$te1."',telefono2=".$te2.",telefono3=".$te3.",boletin='".$bol."' WHERE id=".$log." LIMIT 1");
	  if($reg == 1){
		$sav = $db->query("UPDATE persona SET nombres='".$nom."',apellidos='".$ape."',dni='".$dni."' WHERE id=".$log." LIMIT 1");
	  }else{
		if($pag == ""){$pag = "NULL";}else{$pag = "'".$pag."'";}
		$sav = $db->query("UPDATE empresa SET ruc='".$ruc."',nombrecomercial='".$com."',nombrecontacto='".$con."',direccion='".$dir."',descripcion='".$des."',paginaweb=".$pag." WHERE id=".$log." LIMIT 1");
	  }
	  if($sav){
		header("location:".URL_PATH."micuenta/?status=ok");
	  }
	}else{
	  $msg = '<div class="err itp">'.$msg.'</div>';
	}
  }
  
  $dat = $db->fetchObject("SELECT id,usuariotipo,email,imagen,telefono1,telefono2,telefono3,boletin FROM usuario WHERE id=".$log." LIMIT 0,1");
  $nreg = $dat->usuariotipo; if($nreg == '1'){$ntip = "Persona natural";}else{$ntip = "Empresa";}
  $nmai = $dat->email;
  $nima = $dat->imagen; if($nima != ""){$nimg = URW_PATH."imagenes/usuarios/".$nima;}else{$nimg = URL_PATH."assets/img/usr.jpg";}
  $nte1 = $dat->telefono1; $nte2 = $dat->telefono2; $nte3 = $dat->telefono3; $nbol = $dat->boletin;
  if($dat->usuariotipo == '1'){
	$dap = $db->fetchObject("SELECT nombres,apellidos,dni FROM persona WHERE id=".$log." LIMIT 0,1");
	$nnom = testsho($dap->nombres); $nape = testsho($dap->apellidos); $ndni = testsho($dap->dni);
  }else{
	$dap = $db->fetchObject("SELECT empresatipo,ruc,nombrecomercial,nombrecontacto,direccion,descripcion,paginaweb FROM empresa WHERE id=".$log." LIMIT 0,1");
	$ntie = $dap->empresatipo; if($ntie == '1'){$ntie = "Contructora";}else{$ntie = "Agente inmobiliario";}
	$nruc = testsho($dap->ruc); $ncom = testsho($dap->nombrecomercial); $ncon = testsho($dap->nombrecontacto); $ndir = testsho($dap->direccion); $ndes = testsho($dap->descripcion);
	$nweb = testsho($dap->paginaweb);
  }
  
  $page = "micuenta";
  include(DIR_PATH."assets/includes/header.php");?>  
<div class="ubi">Mi cuenta - Mi perfil</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
  <div class="sub">Mi perfil</div>
  <div class="frc">
  <? if($_GET["status"] == "ok"){?><div class="god itp">Datos guardados correctamente</div><? }?>
  <?=$msg;?>
  <form id="frmCue" method="post" action="<?=URI_PATH;?>" enctype="multipart/form-data">
  	<div class="mod"><div class="lbl">Tipo de registro:</div><div class="flef"><?=$ntip;?><input type="hidden" name="hdnreg" id="hdnreg" value="<?=$nreg;?>" /></div><div class="flot"></div></div>
<?
  if($ntip == "Empresa"){?>
	<div class="mod"><div class="lbl">Tipo de empresa:</div><div class="flef"><?=$ntie;?></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">RUC:</div><div class="flef"><input type="text" name="txtruc" id="txtruc" class="txt" maxlength="11" value="<?=$nruc;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Nombre comercial:</div><div class="flef"><input type="text" name="txtcom" id="txtcom" class="txt txg" maxlength="52" value="<?=$ncom;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Nombre de contacto:</div><div class="flef"><input type="text" name="txtcon" id="txtcon" class="txt txg" maxlength="52" value="<?=$ncon;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Email:</div><div class="flef"><?=$nmai;?></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Direcci&oacute;n:</div><div class="flef"><input type="text" name="txtdir" id="txtdir" class="txt txg" maxlength="80" value="<?=$ndir;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Tel&eacute;fono 1:</div><div class="flef"><input type="text" name="txtte1" id="txtte1" class="txt" maxlength="14" value="<?=$nte1;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Tel&eacute;fono 2:</div><div class="flef"><input type="text" name="txtte2" id="txtte2" class="txt" maxlength="14" value="<?=$nte2;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Tel&eacute;fono 3:</div><div class="flef"><input type="text" name="txtte3" id="txtte3" class="txt" maxlength="14" value="<?=$nte3;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Descripci&oacute;n:</div><div class="flef"><textarea name="txtdes" id="txtdes" class="tex txg"><?=$ndes;?></textarea></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">P&aacute;gina web:</div><div class="flef"><input type="text" name="txtpag" id="txtpag" class="txt txg" maxlength="100" value="<?=$nweb;?>" /></div><div class="flot"></div></div>
<?
  }else{?>
    <div class="mod"><div class="lbl">Email:</div><div class="flef"><?=$nmai;?></div><div class="flot"></div></div>
	<div class="mod"><div class="lbl">Nombres:</div><div class="flef"><input type="text" name="txtnom" id="txtnom" class="txt txg" maxlength="52" value="<?=$nnom;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Apellidos:</div><div class="flef"><input type="text" name="txtape" id="txtape" class="txt txg" maxlength="52" value="<?=$nape;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">DNI:</div><div class="flef"><input type="text" name="txtdni" id="txtdni" class="txt" value="<?=$ndni;?>" maxlength="8" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Tel&eacute;fono 1:</div><div class="flef"><input type="text" name="txtte1" id="txtte1" class="txt" maxlength="14" value="<?=$nte1;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Tel&eacute;fono 2:</div><div class="flef"><input type="text" name="txtte2" id="txtte2" class="txt" maxlength="14" value="<?=$nte2;?>" /></div><div class="flot"></div></div>
    <div class="mod"><div class="lbl">Tel&eacute;fono 3:</div><div class="flef"><input type="text" name="txtte3" id="txtte3" class="txt" maxlength="14" value="<?=$nte3;?>" /></div><div class="flot"></div></div>
<?
  }?>
    <div class="mod">
      <div class="lbl">Imagen:</div>
      <div class="slef">
      	<div class="mod"><input type="file" name="filimg" class="txt" /></div>
        <div class="chi">Peso m&aacute;ximo 500kb en formato jpg, gif o png.</div>
      </div>
	  <? if($nima!=""){?>
      	<div class="slef"><img src="<?=$nimg;?>" align="top" /><input type="hidden" name="hdnima" id="hdnima" value="<?=$nimg;?>" /></div>
		<? if($ntip != "Empresa"){?><div class="flef"><input type="button" value="Eliminar imagen" /></div><? }?>
	  <? }?>
      <div class="flot"></div>
    </div>
    <div class="mad"><div class="slef"><input type="checkbox" name="chkbol" value="1"<? if($nbol==1){echo ' checked="checked"';}?> /></div><div class="flef">Deseo recibir informaci&oacute;n adicional v&iacute;a email</div><div class="flot"></div></div>
    <div class="btn btx"><span><input name="btnok" type="submit" value="Actualizar"></span></div>
    <div class="lod"><img src="<?=URL_PATH;?>assets/img/loading.gif" align="absmiddle" /><span class="ver">Procesando...</span></div>
  </form>
  </div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>