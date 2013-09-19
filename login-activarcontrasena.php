<?
  include("config.php");
  
  if($log != ""){header("location:".URL_PATH);}
    
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $cod = testins($_GET["cod"]);
  if($cod == ""){
	header("location:".URL_PATH);
  }else{
	$cod = $db->fetchObject("SELECT id FROM u_recupera WHERE codigo='".$cod."' ORDER BY fechacreacion DESC LIMIT 0,1");
	if($cod->id == ""){
	  header("location:".URL_PATH);
	}else{
	  if(isset($_POST["btnok"])){
		$pwd = testins($_POST["txtpwd"]); $pwr = testins($_POST["txtpwr"]);
		if($pwd=="" || $pwr==""){$msg .= "<div>Ningun campo debe estar vacio.</div>";}
		if($pwd!=$pwr){$msg .= "<div>Las contraseñas no son iguales.</div>";}
		if($msg==""){
		  $upd = $db->query("UPDATE usuario SET contrasena=md5('".$pwd."') WHERE id=".$cod->id." LIMIT 1");
		  $del = $db->query("DELETE FROM u_recupera WHERE id=".$cod->id." LIMIT 1");
		  if($upd && $del){
			header("location:".URL_PATH."recuperar-contrasena/?status=okc");
		  }
		}else{
		  $msg = '<div class="err itp">'.$msg.'</div>';
		}
  	  }
	}
  }
  $page = "login";
  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi"><a href="<?=URL_PATH;?>">Inicio</a> | Recuperar contrase&ntilde;a</div>
<div class="its">
  <div class="bkl bkln">
  	<div class="bkli">
      <div class="suv">Recuperar contrase&ntilde;a</div><?=$msg;?><form method="post" id="frmRecAct" name="frmRecAct" action="<?=URI_PATH;?>"><div class="mod">Ingrese nueva contrase&nacute;a:</div><div class="mad"><input type="password" id="txtpwd" name="txtpwd" class="txt" /></div><div class="mod">Repetir nueva contrase&nacute;a:</div><div class="mad"><input type="password" id="txtpwr" name="txtpwr" class="txt" /></div><div class="btn btx"><span><input type="submit" name="btnok" value="Cambiar"></span></div></form>
  	</div>
  </div>
</div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>