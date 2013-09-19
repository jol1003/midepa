<?
  include("config.php");
  if($log == ""){header("location:".URL_PATH);}
  
  $db = new mySQL(DB_USER,DB_PASS,DB_NAME);
  
  $id = testins($_GET["id"]); $inm = testins($_GET["inm"]); if(!is_numeric($id) || !is_numeric($inm)){header("location:".URL_PATH);}
  
  $gal = $db->fetchObject("SELECT id,imagen FROM inmueblegaleria WHERE id=".$id." AND inmueble_id='".$inm."' LIMIT 0,1");
  $usr = $db->fetchObject("SELECT usuario_id,categoria FROM inmueble WHERE id=".$inm." LIMIT 0,1");
  if($gal->id == $id && $usr->usuario_id == $log){
	$img = $gal->imagen;
	$upd = $db->query("UPDATE inmueble SET imagen='".$gal->imagen."' WHERE id=".$inm." LIMIT 1");
	if($upd){
	  if($usr->categoria == 1){
		include(DIR_PATH."assets/includes/xml_inmuebles_home.php");
	  }
	}
	header("location:".URL_PATH."micuenta-editarpublicacion/?id=".$inm."#inmimg");
  }else{
	header("location:".URL_PATH);
	
  }
?>