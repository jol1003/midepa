<?
  include("config.php");
  
  if($log != ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $cod = testins($_GET["cod"]);
  if($cod == ""){
	header("location:".URL_PATH);
  }else{
	$cod = $db->fetchObject("SELECT id FROM u_confirma WHERE codigo='".$cod."' ORDER BY fechacreacion DESC LIMIT 0,1");
	if($cod->id == ""){
	  header("location:".URL_PATH);
	}else{
	  $upd = $db->query("UPDATE usuario SET estado='1' WHERE id=".$cod->id." LIMIT 1");
	  $reg = $db->fetchObject("SELECT usuariotipo FROM usuario WHERE id=".$cod->id." LIMIT 0,1");
	  if($reg->usuariotipo == '1'){
		$upd = $db->query("UPDATE persona SET estado='1' WHERE id=".$cod->id." LIMIT 1");
	  }else{
		$upd = $db->query("UPDATE empresa SET estado='1' WHERE id=".$cod->id." LIMIT 1");
		if($upd){
		  include(DIR_PATH."assets/includes/xml_empresas_home.php");
		}
	  }
	  $del = $db->query("DELETE FROM u_confirma WHERE id=".$cod->id);
	  if($upd && $del){
		$cod = $db->fetchObject("SELECT usuariotipo,email FROM usuario WHERE id=".$cod->id." LIMIT 0,1");
		if($cod->usuariotipo=="1"){$nom=$db->fetchObject("SELECT nombres,apellidos FROM persona WHERE id=".$cod->id." LIMIT 0,1");$nom=testsho($nom->nombres." ".$nom->apellidos);}else{$nom=$db->fetchObject("SELECT nombrecontacto FROM empresa WHERE id=".$cod->id." LIMIT 0,1");$nom=testsho($nom->nombrecontacto);}
		$mai = $cod->email;
	  	$men = '<div style="padding-bottom:15px;">EN HORABUENA '.$nom.', usted ha activado su cuenta en midepa.pe</div><div style="padding-bottom:15px;">Ahora podra publicar cualquier aviso de inmueble. Puede comenzar por aqui:</div><div style="padding-bottom:20px;"><a href="'.URL_PATH.'login/" target="_blank">'.URL_PATH.'login/</a></div>';
	  	include(DIR_PATH."assets/includes/mail-enviar.php");
	  	mail($mai,'midepa.pe | Confirmacion de activacion de cuenta',$men,$hed);
	  }else{
		$msg = '<div class="err itp">Ha ocurrido un error. Int&eacute;ntelo de nuevo.</div>';
	  }
	}
  }
  $page = "login";
  include(DIR_PATH."assets/includes/header.php");?>
<div class="ubi"><a href="<?=URL_PATH;?>">Inicio</a> | Activar cuenta de registro</div>
<div class="its">
  <div class="bkl bkln">
  	<div class="bkli">
      <div class="suv">Activaci&oacute;n de cuenta de registro</div>
	  <div class="bld"><? if($msg == ""){?><div class="mod">ENHORABUENA!! Su cuenta de registro ha sido activada exitosamente.</div><div class="mod">Ahora puede comenzar <span class="ahv"><a href="<?=URL_PATH."login/";?>">iniciando sesi&oacute;n aqu&iacute;.</a></span></div><? }else{echo $msg;}?></div>
  	</div>
  </div>
</div>
<?
  include(DIR_PATH."assets/includes/footer.php");?>