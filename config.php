<?
  session_start();
  // Datos Conexion DB
  /*define("DB_HOST","localhost");
  define("DB_USER","generaco_usrmpa");
  define("DB_PASS","mpa2015*eunji");
  define("DB_NAME","generaco_midepadata");*/
define("DB_HOST","localhost");
define("DB_USER","root");
define("DB_PASS","123456");
define("DB_NAME","generaco_midepadata");

  define("URL_PATH","http://".$_SERVER[HTTP_HOST]."/midepa/");
  define("URI_PATH","http://".$_SERVER[HTTP_HOST].$_SERVER['REQUEST_URI']);
  define("DIR_PATH",dirname(__FILE__)."/");
  define("URW_PATH","http://".$_SERVER[HTTP_HOST]."/w/");
  
  include("assets/includes/class/mysql.class.php");
  include("assets/includes/functions.php");
  
  /*Datos del logueado*/
  $log = $_SESSION["gnr_usr"];
  $ltip = $_SESSION["gnr_tip"];
  $lusn = $_SESSION["gnr_nom"];
?>