<?php
include("config.php");
if($log == ""){header("location:".URL_PATH);}
  
$db = new mySQL(DB_USER,DB_PASS,DB_NAME);
$id = $_GET['id'];
$sqlD = "DELETE FROM pago_inmueble_detalle_temp WHERE  id_pago_inmueble = '".$_SESSION['idcar']."' AND id_inmueble = ".$id."";
$queryD = $db->query($sqlD);

header("location:".URL_PATH."pago-publicaciones.php");
?>