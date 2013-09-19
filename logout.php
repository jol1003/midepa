<? include("config.php");
  	$_SESSION["gnr_usr"] = "";
  	$_SESSION["gnr_nom"] = "";
  	$_SESSION['idcar']="";
	unset($_SESSION['idcar']);
  	session_unset();
  	session_destroy();
  	$parametros_cookies = session_get_cookie_params(); 
	setcookie(session_name(),0,1,$parametros_cookies["idcar"]);
	
	$_SESSION = array();
  	header("location: ".URL_PATH);
?>
