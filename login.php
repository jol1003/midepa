<?
  include("config.php");
  
  if($log != ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  if(isset($_POST["btnok"])){
  	$user = testins($_POST["txtusr"]);
	$password = testins($_POST["txtpwd"]);
	$sql = sprintf("SELECT id,usuariotipo,imagen,estado FROM usuario WHERE email='%s' AND contrasena=md5('%s') AND estado='1' LIMIT 0,1",$user,$password);
	$login = $db->fetchObject($sql);
	if($login->id != ""){
	  if($login->estado == '1'){
		if($login->usuariotipo == '1'){
		  $nom = $db->fetchObject("SELECT nombres,apellidos FROM persona WHERE id=".$login->id." LIMIT 0,1");
		  $nom = testsho($nom->nombres." ".$nom->apellidos);
		}else{
		  $nom = $db->fetchObject("SELECT nombrecontacto FROM empresa WHERE id=".$login->id." LIMIT 0,1");
		  $nom = testsho($nom->nombrecontacto);
		}
		$upd = $db->query("UPDATE u_usuario SET fechaacceso=NOW() WHERE id=".$login->id." LIMIT 1");
	  	$_SESSION["gnr_usr"] = $login->id;
		$_SESSION["gnr_tip"] = $login->usuariotipo;
	  	$_SESSION["gnr_nom"] = $nom;
		if($_SESSION["gnr_pag"] == ""){
		  header("location:".URL_PATH."micuenta/");
		}else{
		  header("location:".$_SESSION["gnr_pag"]);
		}
	  }else{
	    header("location:".URL_PATH."login/?err=1");
	  }
	}else{
	  header("location:".URL_PATH."login/?err=2");
	}
  }
  $err = $_GET["err"]; if($err == 1){$msg = "Su cuenta a&uacute;n no se encuentra activa. Debe revisar su correo con el cual se registr&oacute; y dar click en el enlace \"confirmar\" en el mensaje.";}elseif($err == 2){$msg = "El correo o la contrase&ntilde;a no coinciden";}
  $page = "login";
  include(DIR_PATH."assets/includes/header.php");?>

<div class="ubi"><a href="">Inicio</a> | Login</div>
<div class="its">
  <div class="izql">
  	<div class="mad"><div class="suv">&iquest;A&uacute;n no tienes cuenta en Midepa.pe?</div></div>
  	<div class="mad">Elige una de las 2 alternativas:</div>
    <div class="itg">
  	  <div class="mod">1. Soy un usuario particular y quiero publicar un anuncio.</div>
      <div class="ahv"><a href="<?=URL_PATH?>registro/">Reg&iacute;strate gratis</a></div>
    </div>
    <div class="its">
      <div class="mod">2. Soy una empresa inmobiliaria o corredor independiente y quiero publicar mi cartera de inmuebles.</div>
      <div class="ahv"><a href="<?=URL_PATH?>registro/">Reg&iacute;strate gratis</a></div>
    </div>
  </div>
  <div class="bkl">
  	<div class="bkli">
      <div class="suv">Puedes acceder automáticamente a Midepa.pe</div>
<?
  if($msg != ""){?><div class="mod"><div class="err"><?=$msg;?></div></div><? }?>
      <form method="post" action="">
      	<div class="mod">Correo electr&oacute;nico:</div>
      	<div class="mad"><input type="text" name="txtusr" class="txt" /></div>
      	<div class="mod">Contrase&ntilde;a:</div>
      	<div class="mad"><input type="password" name="txtpwd" class="txt" /></div>
      	<div class="btn btx"><span><input type="submit" name="btnok" value="Entrar"></span></div>
        <div><a href="<?=URL_PATH?>recuperar-contrasena/">&iquest;Olvidaste tu contrase&ntilde;a?</a></div>
      </form>
  	</div>
  </div>
  <div class="flot"></div>
</div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>