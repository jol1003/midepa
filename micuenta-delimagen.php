<?
  include("config.php");
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $id = testins($_GET["id"]); $inm = testins($_GET["inm"]); if(!is_numeric($id) || !is_numeric($inm)){header("location:".URL_PATH);}
  
  $gal = $db->fetchObject("SELECT id,imagen FROM inmueblegaleria WHERE id=".$id." AND inmueble_id='".$inm."' LIMIT 0,1");
  $usr = $db->fetchObject("SELECT usuario_id FROM inmueble WHERE id=".$inm." LIMIT 0,1");
  if($gal->id == $id && $usr->usuario_id == $log){
	$img = $gal->imagen;
	$gal = $db->fetchObject("SELECT COUNT(id) AS can FROM inmueblegaleria WHERE inmueble_id='".$inm."' LIMIT 0,1");
	if($gal->can > 1){
	  if(file_exists(DIR_PATH."w/imagenes/inmuebles/".$img)){unlink(DIR_PATH."w/imagenes/inmuebles/".$img);}
	  if(file_exists(DIR_PATH."w/imagenes/inmuebles/thumbs/".$img)){unlink(DIR_PATH."w/imagenes/inmuebles/thumbs/".$img);}
	  $del = $db->query("DELETE FROM inmueblegaleria WHERE id=".$id." LIMIT 1");
	  header("location:".URL_PATH."micuenta-editarpublicacion/?id=".$inm."#inmimg");
	}else{
	  header("location:".URL_PATH."micuenta-editarpublicacion/?id=".$inm."&status=err#inmimg");
	}
  }else{
	header("location:".URL_PATH);
  }
?>