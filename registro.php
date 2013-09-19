<?
  include("config.php");
  include(DIR_PATH."assets/includes/class/phpthumb/ThumbLib.inc.php");
  
  if($log != ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  if(isset($_POST["btnok"])){
	$reg = $_POST["cboreg"];
	//PERSONA NATURAL
	$nom = testins($_POST["txtnom"]); $ape = testins($_POST["txtape"]); $dni = testins($_POST["txtdni"]);
	//EMPRESA
	$emp = $_POST["cboemp"]; $ruc = testins($_POST["txtruc"]); $com = testins($_POST["txtcom"]); $con = testins($_POST["txtcon"]); $img = $_FILES["filimg"]["name"];
	$tel = testins($_POST["txttel"]); $dir = testins($_POST["txtdir"]); $pag = testins($_POST["txtpag"]); $des = testins($_POST["txtdes"]);
	//GENERAL
	$mai = testins($_POST["txtmai"]); $pwd = testins($_POST["txtpwd"]); $pwr = testins($_POST["txtpwr"]);
	$bol = $_POST["chkbol"]; if($bol == ""){$bol = 0;}
	$ter = $_POST["chkter"];
	
	if($reg == 1){
	  if($nom == ""){$msg.="- Nombres";}
	  if($ape == ""){$msg.="- Apellidos";}
	  if($dni == ""){$msg.="- DNI";}
	}else{
	  if($com == ""){$msg.='<div class="ler">- Nombre comercial</div>';}
	  if($ruc == ""){$msg.='<div class="ler">- RUC</div>';}
	  if($con == ""){$msg.='<div class="ler">- Nombre de contacto</div>';}
	  if($tel == ""){$msg.='<div class="ler">- Telefono</div>';}
	  if($dir == ""){$msg.='<div class="ler">- Direccion</div>';}
	  if($img == ""){$msg.='<div class="ler">- Logo</div>';}
	  if($des == ""){$msg.='<div class="ler">- Descripcion</div>';}
	}
	if($mai == ""){$msg.='<div class="ler">- Email</div>';}
	if($pwd == ""){$msg.='<div class="ler">- Contraseña</div>';}
	if($pwr == ""){$msg.='<div class="ler">- Repetir contraseña</div>';}
	if($msg != ""){$msg='<div>* Los siguientes campos no deben estar vacios:</div>'.$msg.'<div class="flot"></div>';}
	if(!preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $mai)){$msg.="<div>* El Email no es valido.</div>";}
	if($pwd != $pwr){$msg.="<div>* Las contraseñas no son iguales.</div>";}
	if($reg == 2 && $img != ""){
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
	if($ter == ""){$msg.='<div>* Aceptar los términos y condiciones de uso</div>';}
	
	if($msg == ""){
	  $maiuse = $db->fetchObject("SELECT id FROM usuario WHERE email='".$mai."' LIMIT 0,1");
	  if($maiuse->id == ""){//si el correo no se repite
	  	$idx = $db->fetchObject("SELECT MAX(id)+1 AS idx FROM usuario LIMIT 0,1"); if($idx->idx == ""){$idx->idx = 1;}
		if($reg == 1){
		  $sav = $db->query("INSERT INTO usuario(id,usuariotipo,email,contrasena,boletin,estado,fechaacceso,fechacreacion) VALUES(".$idx->idx.",'".$reg."','".$mai."','".md5($pwd)."','".$bol."','0',NOW(),NOW())");
		}else{
		  $sav = $db->query("INSERT INTO usuario(id,usuariotipo,email,contrasena,telefono1,boletin,estado,fechaacceso,fechacreacion) VALUES(".$idx->idx.",'".$reg."','".$mai."','".md5($pwd)."','".$tel."','".$bol."','0',NOW(),NOW())");
		}
	  	if($sav){
		  if($reg == 1){
		  	$sav = $db->query("INSERT INTO persona(id,nombres,apellidos,dni,estado) VALUES(".$idx->idx.",'".$nom."','".$ape."','".$dni."','0')");
	  	  }else{
		  	$img = $idx->idx.".jpg";
		  	$thb = PhpThumbFactory::create($_FILES["filimg"]["tmp_name"]);
		  	$thb->adaptiveResize(176, 62);
		  	//$thb->resize(176, 62);
		  	$thb->save(DIR_PATH."w/imagenes/usuarios/".$img);
			if($pag == ""){$pag = "NULL";}else{$pag = "'".$pag."'";} //porque puede ser vacio
		  	$sav = $db->query("INSERT INTO empresa(id,empresatipo,ruc,nombrecomercial,nombrecontacto,direccion,descripcion,paginaweb,estado) VALUES(".$idx->idx.",'".$emp."','".$ruc."','".$com."','".$con."','".$dir."','".$des."',".$pag.",'0')");
		  	$upd = $db->query("UPDATE usuario SET imagen='".$img."' WHERE id=".$idx->idx." LIMIT 1");
	  	  }
	 	}
	  	if($sav){
		  $cod = md5(time());
		  $sav = $db->query("INSERT INTO u_confirma(id,codigo,estado,fechacreacion) VALUES(".$idx->idx.",'".$cod."','1',NOW())");
		  if($sav){
		  	$men = '<div style="padding-bottom:15px;">Usted se ha registrado en midepa.pe</div><div style="padding-bottom:15px;">Para activar su cuenta, haga click en el enlace de abajo o copielo y peguelo en la barra de direcciones de su explorador:</div><div style="padding-bottom:20px;"><a href="'.URL_PATH.'activar-cuenta/'.$cod.'" target="_blank">'.URL_PATH.'activar-cuenta/'.$cod.'</a></div>';
			//include(DIR_PATH."assets/includes/mail-enviar.php");
			$hed  = "MIME-Version: 1.0\n";
  			$hed .= "Content-Type: text/html; charset=UTF-8\n";
  			$hed .= "From: Midepa.pe <info@midepa.pe>\n";
  			$hed .= "Reply-To: <info@midepa.pe>\n";
  			$hed .= "Return-Path: <info@midepa.pe>\n";
  			$hed .= "Envelope-from: <info@midepa.pe>";
  			$men = '<div style="font-family:Arial,Helvetica,sans-serif;font-size:13px;"><div style="background:#3EA325;"><a href="'.URL_PATH.'" target="_blank"><img src="'.URL_PATH.'assets/img/mail.logo.jpg" border="0" /></a></div><div style="padding:10px;">'.$men.'</div><div style="background:#3EA325;padding:8px;color:#FFFFFF;">Tu privacidad es importante para nosotros. Lee nuestros <a href="'.URL_PATH.'terminos-y-condiciones/" style="color:#FFFFFF;" target="_blank">T&eacute;rminos y condiciones de uso.</a></div></div>';
			
		  	mail($mai,'midepa.pe | Confirmacion de registro',$men,$hed);
			header("location:".URL_PATH."registro/?status=ok");
		  }
	  	}
	  }else{
		$msg = '<div class="err">El correo electr&oacute;nico electr&oacute;nico ya existe.</div>';
	  }
	}else{
	  $msg = '<div class="err">'.$msg.'</div>';
	}
  }
  
  $page = "mi-perfil";
  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi"><a href="<?=URL_PATH;?>">Inicio</a> | Reg&iacute;strate</div>
<div class="its">
<?
  if($_GET["status"] == "ok"){?>
  <div class="bkl bkln">
  	<div class="bkli">
      <div class="suv">Su registro se ha realizado con &eacute;xito</div>
      <div class="bld"><div class="mod">Un mensaje ha sido enviado a su correo electr&oacute;nico con un enlace para activar su cuenta.</div><div class="mod">Ingrese a su correo electr&oacute;nico y haga click en ese enlace.</div></div><div class="chi">Revise su bandeja de correos no deseados en caso de no visualizar el mensaje enviado.</div>
    </div>
  </div>
<?
  }else{?>
  <div class="mod"><div class="ich">Ventajas de colocar un anuncio</div></div>
  <div class="mod">Registr&aacute;ndote como usuario particular podr&aacute;s acceder a estos servicios Gratis Solo tienes que llenar este formulario y Listo !!!</div>
  <div class="mod">Abrir una cuenta de Usuario Particular Te permite publicar anuncios, subir fotos, revisar solicitudes u ofertas de personas interesadas en tu anuncio, ver tu lista de anuncios publicados, recibir alertas de expiraci&oacute;n de tus anuncios, publicar tus datos de contacto, localizar tu propiedad en un mapa para su f&aacute;cil ubicacion por parte de los interesados etc.</div>
  <?=$msg;?>
  <div class="frr">
  <form method="post" id="frmReg" action="<?=URI_PATH;?>" enctype="multipart/form-data">
    <div class="mod"><div class="lbl">Tipo de registro</div><div class="flef"><select name="cboreg" id="cboreg" class="txt txg"><option value="1" selected="selected">Persona natural</option><option value="2">Empresa</option></select></div><div class="flot"></div></div>
    <div id="dvreg">
  	  <div class="izql">
      	<div class="mod"><div class="lbl">Nombres</div><div class="flef"><input type="text" name="txtnom" id="txtnom" class="txt txg" maxlength="52" /></div><div class="flot"></div></div>
        <div class="mod"><div class="lbl">DNI</div><div class="flef"><input type="text" name="txtdni" id="txtdni" class="txt" maxlength="8" /></div><div class="flot"></div></div>
        <div class="mod"><div class="lbl">Contrase&ntilde;a</div><div class="flef"><input type="password" name="txtpwd" id="txtpwd" class="txt txg" /></div><div class="flot"></div></div>
  	  </div>
  	  <div class="izql izqln">
  	  	<div class="mod"><div class="lbl">Apellidos</div><div class="flef"><input type="text" name="txtape" id="txtape" class="txt txg" maxlength="52" /></div><div class="flot"></div></div>
        <div class="mod"><div class="lbl">Email</div><div class="flef"><input type="text" name="txtmai" id="txtmai" class="txt txg" maxlength="52" /></div><div class="flot"></div></div>
        <div class="mod"><div class="lbl">Repetir contrase&ntilde;a</div><div class="flef"><input type="password" name="txtpwr" id="txtpwr" class="txt txg" /></div><div class="flot"></div></div>
  	  </div>
   	  <div class="flot"></div>
    </div>
    <div class="its"><div class="slef"><input type="checkbox" name="chkbol" value="1" /></div><div class="zlef">Deseo recibir informaci&oacute;n adicional vía email</div><div class="slef"><input type="checkbox" name="chkter" id="chkter" value="1" /></div><div class="flef">Acepto los t&eacute;rminos y condiciones de uso (<a href="<?=URL_PATH;?>terminos/" target="_blank">Ver t&eacute;rminos aqui</a>)</div><div class="flot"></div></div>
    <div class="btn btx"><span><input type="submit" name="btnok" value="Enviar" /></span></div>
  </form>
  </div>
<?
  }?>
</div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>