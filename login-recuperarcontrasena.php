<?
  include("config.php");
  
  if($log != ""){header("location:".URL_PATH);}
    
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $sta = testins($_GET["status"]); if($sta!=""&&$sta!="ok"&&$sta!="okc"){header("location:".URL_PATH);}
  
  if(isset($_POST["btnok"])){
	$mae = testins($_POST["txtusr"]);
	if(preg_match("/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/", $mae)){
	  $mai = $db->fetchObject("SELECT id,email FROM usuario WHERE email='".$mae."' LIMIT 0,1");
	  if($mai->id != ""){
		$cod = md5(time());
		$exi = $db->fetchObject("SELECT id FROM u_recupera WHERE id=".$mai->id." LIMIT 0,1");
		if($exi->id == ""){
		  $sav = $db->query("INSERT INTO u_recupera(id,codigo,fechacreacion) VALUES('".$mai->id."','".$cod."',NOW())");
		}else{
		  $sav = $db->query("UPDATE u_recupera SET codigo='".$cod."' WHERE id=".$mai->id." LIMIT 1");
		}
		if($sav){
		  $men = '<div style="padding-bottom:15px;">Recuperar contrase&ntilde;a</div><div style="padding-bottom:15px;">Para recuperar su contrase&ntilde;a, :</div><div style="padding-bottom:20px;"><a href="'.URL_PATH.'recuperar-contrasena/code/'.$cod.'" target="_blank">'.URL_PATH.'recuperar-contrasena/code/'.$cod.'</a></div>';
		  include(DIR_PATH."assets/includes/mail-enviar.php");
		  if(mail($mai->email,'midepa.pe | Recuperar contrasena',$men,$hed)){
			header("location:".URL_PATH."recuperar-contrasena/?status=ok");
		  }else{
			$msg = '<div class="err itp">Ha ocurrido un error. Int&eacute;ntelo de nuevo.</div>';
		  }
		}else{
		  $msg = '<div class="err itp">Ha ocurrido un error. Int&eacute;ntelo de nuevo.</div>';
		}
	  }else{
		$msg = '<div class="err itp">No se ha registrado este correo electr&oacute;nico</div>';
	  }
	}else{
	  $msg = '<div class="err itp">El correo electr&oacute;nico no es v&aacute;lido</div>';
	}
  }
  $page = "login";
  include(DIR_PATH."assets/includes/header.php");?>

<div class="ubi"><a href="<?=URL_PATH;?>">Inicio</a> | Recuperar contrase&ntilde;a</div>
<div class="its">
  <div class="bkl bkln">
  	<div class="bkli">
      <div class="suv">Recuperar contrase&ntilde;a</div><? if($sta=="ok"){?><div class="bld"><div class="mod">Un mensaje ha sido enviado a su correo electr&oacute;nico con instrucciones para modificar su contrase&ntilde;a.</div><div class="mod">Este enlace estar&aacute; activo por un lapso de 24 horas, luego de los cuales podr&aacute; realizar otra vez esta operaci&oacute;n de recuperar contrase&ntilde;a en caso de un error.</div></div><div class="chi">Revise su bandeja de correos no deseados en caso de no visualizar el mensaje enviado.</div><? }elseif($sta=="okc"){?><div class="bld"><div class="mod">Su contrase&nacute;a ha sido modificada correctamente.</div>Puede ingresar a su perfil o publicar un inmueble <a href="<?=URL_PATH;?>login/">iniciando sesi&oacute;n</a></div><? }else{echo $msg;?><form method="post" id="frmRec" name="frmRec" action="<?=URI_PATH;?>"><div class="mod">Correo electr&oacute;nico con el que se registr&oacute;:</div><div class="mad"><input type="text" id="txtusr" name="txtusr" value="<?=$mae;?>" class="txt" /></div><div class="btn btx"><span><input type="submit" name="btnok" value="Enviar"></span></div></form><? }?>
  	</div>
  </div>
</div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>