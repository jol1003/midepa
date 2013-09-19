<?php
include("config.php");
if($log == ""){header("location:".URL_PATH);}
  
$db = new mySQL(DB_USER,DB_PASS,DB_NAME);
$id = $_POST['id'];
$cant = $_POST['cant'];
$sqlD = "UPDATE pago_inmueble_detalle_temp SET  cantidad_pago_detalle = '".$cant."' where id_pago_detalle = ".$id."  ";
$queryD = $db->query($sqlD);

?>