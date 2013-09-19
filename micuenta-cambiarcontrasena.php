<?
  include("config.php");
  include(DIR_PATH."assets/includes/class/phpthumb/ThumbLib.inc.php");
  
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  if(isset($_POST["btnok"])){
	$pwd = testins($_POST["txtpwd"]); $pwn = testins($_POST["txtpwn"]); $pwr = testins($_POST["txtpwr"]);
	if($pwd!="" && $pwn!="" && $pwr!=""){
	  $pwd = $db->fetchObject("SELECT id FROM usuario WHERE contrasena=md5('".$pwd."') AND id=".$log." LIMIT 0,1");
	  if($pwd->id != ""){
	  	if($pwn == $pwr){
		  $upd = $db->query("UPDATE usuario SET contrasena=md5('".$pwn."') WHERE id=".$log." LIMIT 1");
		  if($upd){
		  	header("location:".URL_PATH."micuenta-contrasena/?status=ok");
		  }else{
		  	$msg='<div class="err itp">Ha ocurrido un error. Int&eacute;ntelo de nuevo.</div>';
		  }
	  	}else{
		  $msg='<div class="err itp">Las nuevas contrase&ntilde;as no son iguales.</div>';
	  	}
	  }else{
	  	$msg='<div class="err itp">La contrase&ntilde;a anterior no coincide.</div>';
	  }
	}else{
	  $msg='<div class="err itp">Ning&uacute;n campo puede estar vac&iacute;o.</div>';
	}
  }
  $page = "micuenta";
  include(DIR_PATH."assets/includes/header.php");?>  
<div class="ubi">Mi cuenta - Mi perfil</div>
<? include(DIR_PATH."assets/includes/micuenta-menu.php");?>
<div class="den">
  <div class="sub">Mi perfil - Cambiar contrase&ntilde;a</div>
  <div class="frc mpw">
<? if($_GET["status"]=="ok"){?><div class="god itp">Su contrase&ntilde;a ha sido guardada correctamente.</div><? }else{echo $msg;?><form id="frmPwd" method="post" action="<?=URI_PATH;?>"><div class="mod"><div class="lbl">Contrase&ntilde;a anterior:</div><div class="flef"><input type="password" name="txtpwd" id="txtpwd" class="txt txg" /></div><div class="flot"></div></div><div class="mod"><div class="lbl">Nueva Contrase&ntilde;a:</div><div class="flef"><input type="password" name="txtpwn" id="txtpwn" class="txt txg" /></div><div class="flot"></div></div><div class="mad"><div class="lbl">Repetir nueva Contrase&ntilde;a:</div><div class="flef"><input type="password" name="txtpwr" id="txtpwr" class="txt txg" /></div><div class="flot"></div></div><div class="btn btx"><span><input name="btnok" type="submit" value="Cambiar"></span></div></form><? }?>
  </div>
</div>
<div class="flot"></div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>